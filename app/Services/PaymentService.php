<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function generatePaymentReference(): string
    {
        return 'PAY-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
    }

    public function createPayment(Booking $booking, array $data, ?int $receivedBy = null): Payment
    {
        return DB::transaction(function () use ($booking, $data, $receivedBy) {
            $payment = Payment::create([
                'payment_reference' => $data['payment_reference'] ?? $this->generatePaymentReference(),
                'booking_id' => $booking->id,
                'invoice_id' => $booking->invoice?->id,
                'amount' => $data['amount'],
                'method' => $data['method'],
                'status' => $data['status'] ?? PaymentStatus::Paid->value,
                'paid_at' => $data['paid_at'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'received_by' => $receivedBy,
            ]);

            $this->syncBookingAndInvoiceAmounts($booking->refresh());

            return $payment;
        });
    }

    public function syncBookingAndInvoiceAmounts(Booking $booking): void
    {
        $paid = (float) $booking->payments()
            ->whereNot('status', PaymentStatus::Cancelled->value)
            ->sum('amount');

        $balance = max(0, (float) $booking->total_amount - $paid);

        $booking->update([
            'paid_amount' => number_format($paid, 2, '.', ''),
            'balance_amount' => number_format($balance, 2, '.', ''),
        ]);

        if ($booking->invoice) {
            $status = $balance <= 0.00001
                ? InvoiceStatus::Paid->value
                : ($paid > 0 ? InvoiceStatus::Partial->value : InvoiceStatus::Pending->value);

            $booking->invoice->update([
                'paid_amount' => number_format($paid, 2, '.', ''),
                'balance_amount' => number_format($balance, 2, '.', ''),
                'status' => $status,
            ]);
        }
    }
}

