<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\BookingStoreRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\ActivityLogger;
use App\Services\BookingPricingService;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookings,
        private BookingPricingService $pricing,
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $q = trim((string) $request->query('q', ''));

        $bookings = Booking::query()
            ->with(['customer', 'room', 'roomType'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('booking_number', 'like', "%{$q}%")
                        ->orWhereHas('customer', fn ($c) => $c->where('full_name', 'like', "%{$q}%"))
                        ->orWhereHas('room', fn ($r) => $r->where('room_number', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('bookings.index', [
            'bookings' => $bookings,
            'q' => $q,
            'status' => $status,
            'statuses' => array_map(fn ($s) => $s->value, BookingStatus::cases()),
        ]);
    }

    public function create(Request $request): View
    {
        return view('bookings.create', [
            'customers' => Customer::query()->orderBy('full_name')->limit(300)->get(),
            'roomTypes' => RoomType::query()->orderBy('name')->get(),
            'rooms' => Room::query()->with('roomType')->orderBy('room_number')->get(),
            'defaultTaxRate' => 18.0,
        ]);
    }

    public function store(BookingStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $room = null;
        if (! empty($data['room_id'])) {
            $room = Room::query()->findOrFail($data['room_id']);
        }

        if ($room && (int) $room->room_type_id !== (int) $data['room_type_id']) {
            throw ValidationException::withMessages([
                'room_id' => 'Selected room does not match the room type.',
            ]);
        }

        if (! $this->bookings->roomIsAvailable($data['room_id'] ?? null, $data['check_in_date'], $data['check_out_date'])) {
            throw ValidationException::withMessages([
                'room_id' => 'Room is already booked for the selected dates.',
            ]);
        }

        $rate = (float) ($data['rate_per_night'] ?? ($room?->rate_per_night ?? RoomType::findOrFail($data['room_type_id'])->default_rate));
        $calc = $this->pricing->calculate(
            $data['check_in_date'],
            $data['check_out_date'],
            $rate,
            (float) ($data['discount_amount'] ?? 0),
            (float) ($data['extra_services_amount'] ?? 0),
            (float) ($data['tax_rate'] ?? 0),
            0,
        );

        $booking = $this->bookings->create([
            ...$data,
            'rate_per_night' => number_format($rate, 2, '.', ''),
            'nights' => $calc['nights'],
            'subtotal' => $calc['subtotal'],
            'tax_amount' => $calc['tax_amount'],
            'total_amount' => $calc['total_amount'],
            'paid_amount' => '0.00',
            'balance_amount' => $calc['balance_amount'],
        ], createdByUserId: $request->user()->id);

        app(ActivityLogger::class)->log('booking.create', $booking, "Created booking {$booking->booking_number}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Reservation created.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['customer', 'room', 'roomType', 'invoice', 'payments.receiver', 'checkin', 'checkout']);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $booking->load(['customer', 'room', 'roomType']);

        return view('bookings.edit', [
            'booking' => $booking,
            'customers' => Customer::query()->orderBy('full_name')->limit(300)->get(),
            'roomTypes' => RoomType::query()->orderBy('name')->get(),
            'rooms' => Room::query()->with('roomType')->orderBy('room_number')->get(),
        ]);
    }

    public function update(BookingUpdateRequest $request, Booking $booking): RedirectResponse
    {
        $data = $request->validated();

        $room = null;
        if (! empty($data['room_id'])) {
            $room = Room::query()->findOrFail($data['room_id']);
        }

        if ($room && (int) $room->room_type_id !== (int) $data['room_type_id']) {
            throw ValidationException::withMessages([
                'room_id' => 'Selected room does not match the room type.',
            ]);
        }

        if (! $this->bookings->roomIsAvailable($data['room_id'] ?? null, $data['check_in_date'], $data['check_out_date'], $booking->id)) {
            throw ValidationException::withMessages([
                'room_id' => 'Room is already booked for the selected dates.',
            ]);
        }

        DB::transaction(function () use ($booking, $data, $room) {
            $rate = (float) ($data['rate_per_night'] ?? ($room?->rate_per_night ?? RoomType::findOrFail($data['room_type_id'])->default_rate));
            $booking->update([
                ...$data,
                'rate_per_night' => number_format($rate, 2, '.', ''),
            ]);
        });

        $this->bookings->recalculate($booking->refresh());
        $this->bookings->syncRoomStatusForBooking($booking->refresh());

        app(ActivityLogger::class)->log('booking.update', $booking, "Updated booking {$booking->booking_number}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Reservation updated.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $this->bookings->cancel($booking);
        app(ActivityLogger::class)->log('booking.cancel', $booking, "Cancelled booking {$booking->booking_number}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Reservation cancelled.');
    }

    public function checkIn(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'guest_verified' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $this->bookings->checkIn(
                $booking,
                (bool) $request->boolean('guest_verified', true),
                $request->input('notes'),
                $request->user()->id,
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        app(ActivityLogger::class)->log('booking.checkin', $booking, "Checked in booking {$booking->booking_number}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Guest checked in.');
    }

    public function checkOut(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'late_checkout_fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->bookings->checkOut(
            $booking,
            (float) ($data['late_checkout_fee'] ?? 0),
            $data['notes'] ?? null,
            $request->user()->id,
        );

        app(ActivityLogger::class)->log('booking.checkout', $booking, "Checked out booking {$booking->booking_number}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Guest checked out.');
    }
}
