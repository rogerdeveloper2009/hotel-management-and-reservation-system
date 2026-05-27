<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\InvoiceStatus;
use App\Enums\RoomStatus;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(private BookingPricingService $pricing)
    {
    }

    public function generateBookingNumber(): string
    {
        return 'BK-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
    }

    public function generateInvoiceNumber(): string
    {
        return 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
    }

    public function roomIsAvailable(?int $roomId, string $checkInDate, string $checkOutDate, ?int $ignoreBookingId = null): bool
    {
        if (! $roomId) {
            return true;
        }

        $activeStatuses = [
            BookingStatus::Pending->value,
            BookingStatus::Confirmed->value,
            BookingStatus::CheckedIn->value,
        ];

        $query = Booking::query()
            ->where('room_id', $roomId)
            ->whereIn('status', $activeStatuses)
            ->where(function ($q) use ($checkInDate, $checkOutDate) {
                $q->where('check_in_date', '<', $checkOutDate)
                    ->where('check_out_date', '>', $checkInDate);
            });

        if ($ignoreBookingId) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        return ! $query->exists();
    }

    public function create(array $data, ?int $createdByUserId = null): Booking
    {
        return DB::transaction(function () use ($data, $createdByUserId) {
            $data['booking_number'] = $data['booking_number'] ?? $this->generateBookingNumber();
            $data['created_by'] = $createdByUserId;

            $booking = Booking::create($data);

            $this->syncRoomStatusForBooking($booking);

            return $booking;
        });
    }

    public function recalculate(Booking $booking): Booking
    {
        $calc = $this->pricing->calculate(
            checkInDate: $booking->check_in_date->format('Y-m-d'),
            checkOutDate: $booking->check_out_date->format('Y-m-d'),
            ratePerNight: (float) $booking->rate_per_night,
            discountAmount: (float) $booking->discount_amount,
            extraServicesAmount: (float) $booking->extra_services_amount,
            taxRatePercent: (float) $booking->tax_rate,
            paidAmount: (float) $booking->paid_amount,
        );

        $booking->update([
            'nights' => $calc['nights'],
            'subtotal' => $calc['subtotal'],
            'tax_amount' => $calc['tax_amount'],
            'total_amount' => $calc['total_amount'],
            'balance_amount' => $calc['balance_amount'],
        ]);

        if ($booking->invoice) {
            $this->syncInvoiceFromBooking($booking);
        }

        return $booking->refresh();
    }

    public function checkIn(Booking $booking, bool $guestVerified = true, ?string $notes = null, ?int $userId = null): Booking
    {
        return DB::transaction(function () use ($booking, $guestVerified, $notes, $userId) {
            if (! $booking->room_id) {
                throw new \RuntimeException('Room assignment is required before check-in.');
            }

            if (! $this->roomIsAvailable($booking->room_id, $booking->check_in_date->format('Y-m-d'), $booking->check_out_date->format('Y-m-d'), $booking->id)) {
                throw new \RuntimeException('Room is not available for the selected dates.');
            }

            $booking->checkin()->create([
                'checked_in_at' => now(),
                'guest_verified' => $guestVerified,
                'notes' => $notes,
                'created_by' => $userId,
            ]);

            $booking->update(['status' => BookingStatus::CheckedIn->value]);

            Room::whereKey($booking->room_id)->update(['status' => RoomStatus::Occupied->value]);

            $invoice = $booking->invoice ?: Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'booking_id' => $booking->id,
                'issued_at' => now(),
            ]);

            $this->syncInvoiceFromBooking($booking->refresh());

            return $booking->refresh();
        });
    }

    public function checkOut(Booking $booking, float $lateCheckoutFee = 0, ?string $notes = null, ?int $userId = null): Booking
    {
        return DB::transaction(function () use ($booking, $lateCheckoutFee, $notes, $userId) {
            $booking->checkout()->create([
                'checked_out_at' => now(),
                'late_checkout_fee' => $lateCheckoutFee,
                'notes' => $notes,
                'created_by' => $userId,
            ]);

            $booking->update([
                'status' => BookingStatus::CheckedOut->value,
                'extra_services_amount' => (string) (round((float) $booking->extra_services_amount + $lateCheckoutFee, 2)),
            ]);

            $this->recalculate($booking->refresh());

            if ($booking->room_id) {
                Room::whereKey($booking->room_id)->update(['status' => RoomStatus::Cleaning->value]);
            }

            return $booking->refresh();
        });
    }

    public function cancel(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $booking->update(['status' => BookingStatus::Cancelled->value]);

            if ($booking->room_id) {
                Room::whereKey($booking->room_id)->update(['status' => RoomStatus::Available->value]);
            }

            if ($booking->invoice) {
                $booking->invoice->update(['status' => InvoiceStatus::Cancelled->value]);
            }

            return $booking->refresh();
        });
    }

    public function syncInvoiceFromBooking(Booking $booking): void
    {
        $invoice = $booking->invoice;
        if (! $invoice) {
            return;
        }

        $invoice->update([
            'subtotal' => $booking->subtotal,
            'discount_amount' => $booking->discount_amount,
            'extra_services_amount' => $booking->extra_services_amount,
            'tax_amount' => $booking->tax_amount,
            'total_amount' => $booking->total_amount,
            'paid_amount' => $booking->paid_amount,
            'balance_amount' => $booking->balance_amount,
            'status' => $this->invoiceStatusFromAmounts((float) $booking->paid_amount, (float) $booking->balance_amount),
        ]);
    }

    private function invoiceStatusFromAmounts(float $paid, float $balance): string
    {
        if ($balance <= 0.00001) {
            return InvoiceStatus::Paid->value;
        }

        return $paid > 0 ? InvoiceStatus::Partial->value : InvoiceStatus::Pending->value;
    }

    public function syncRoomStatusForBooking(Booking $booking): void
    {
        if (! $booking->room_id) {
            return;
        }

        if (in_array($booking->status->value, [BookingStatus::Pending->value, BookingStatus::Confirmed->value], true)) {
            Room::whereKey($booking->room_id)->update(['status' => RoomStatus::Reserved->value]);
        }

        if ($booking->status === BookingStatus::CheckedIn) {
            Room::whereKey($booking->room_id)->update(['status' => RoomStatus::Occupied->value]);
        }
    }
}
