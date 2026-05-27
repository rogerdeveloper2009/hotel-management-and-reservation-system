<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Requests\PaymentStoreRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\ActivityLogger;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $payments)
    {
    }

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $payments = Payment::query()
            ->with(['booking.customer', 'receiver'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('payment_reference', 'like', "%{$q}%")
                        ->orWhereHas('booking', fn ($b) => $b->where('booking_number', 'like', "%{$q}%"))
                        ->orWhereHas('booking.customer', fn ($c) => $c->where('full_name', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('payments.index', [
            'payments' => $payments,
            'q' => $q,
        ]);
    }

    public function create(Request $request): View
    {
        $bookingId = $request->query('booking_id');
        $booking = $bookingId ? Booking::with(['customer', 'invoice'])->find($bookingId) : null;

        return view('payments.create', [
            'booking' => $booking,
            'bookings' => $booking
                ? collect([$booking])
                : Booking::query()->with('customer')->latest()->limit(200)->get(),
            'methods' => array_map(fn ($m) => $m->value, PaymentMethod::cases()),
            'statuses' => array_map(fn ($s) => $s->value, PaymentStatus::cases()),
        ]);
    }

    public function store(PaymentStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $booking = Booking::with('invoice')->findOrFail($data['booking_id']);

        $payment = $this->payments->createPayment($booking, $data, $request->user()->id);
        app(ActivityLogger::class)->log('payment.create', $payment, "Recorded payment {$payment->payment_reference}");

        return redirect()->route('bookings.show', $booking)->with('success', 'Payment recorded.');
    }
}
