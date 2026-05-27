<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $booking->booking_number }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->customer->full_name }} • {{ $booking->check_in_date->format('Y-m-d') }} → {{ $booking->check_out_date->format('Y-m-d') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('bookings.edit', $booking) }}" class="rounded-xl bg-black/90 px-4 py-2 text-sm font-semibold text-white hover:bg-black dark:bg-white/10 dark:hover:bg-white/15">Edit</a>
                <a href="{{ route('payments.create', ['booking_id' => $booking->id]) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Add Payment</a>

                @if ($booking->status->value !== 'cancelled' && $booking->status->value !== 'checked_out')
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500" onclick="return confirm('Cancel this reservation?')">Cancel</button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-ui.card title="Summary" class="lg:col-span-1">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ str_replace('_', ' ', $booking->status->value) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Room</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $booking->room?->room_number ?? 'Not assigned' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Rate / Night</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($booking->rate_per_night)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Nights</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $booking->nights }}</dd>
                </div>
                <div class="pt-2 border-t border-black/5 dark:border-white/10">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Subtotal</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($booking->subtotal)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Discount</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($booking->discount_amount)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Extra Services</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($booking->extra_services_amount)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Tax ({{ $booking->tax_rate }}%)</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($booking->tax_amount)</dd>
                </div>
                <div class="pt-2 border-t border-black/5 dark:border-white/10">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Total</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($booking->total_amount)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Paid</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($booking->paid_amount)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Balance</dt>
                    <dd class="mt-1 font-semibold text-gray-900 dark:text-white">@rwf($booking->balance_amount)</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="Operations" class="lg:col-span-2">
            <div class="flex flex-wrap items-center gap-3">
                @if ($booking->status->value === 'pending' || $booking->status->value === 'confirmed')
                    <form method="POST" action="{{ route('bookings.checkin', $booking) }}" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="guest_verified" value="1" />
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">Check In</button>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Requires room assignment.</span>
                    </form>
                @elseif ($booking->status->value === 'checked_in')
                    <form method="POST" action="{{ route('bookings.checkout', $booking) }}" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <div>
                            <input name="late_checkout_fee" type="number" step="0.01" min="0" placeholder="Late fee (RWF)" class="w-48 rounded-xl border border-black/10 bg-white/70 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" />
                        </div>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Check Out</button>
                    </form>
                @endif
            </div>

            <div class="mt-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Payments</h3>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                        <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Reference</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Received By</th>
                                <th class="px-4 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 dark:divide-white/10">
                            @forelse ($booking->payments as $payment)
                                <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $payment->payment_reference }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $payment->method->value) }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($payment->amount)</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $payment->receiver?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ optional($payment->paid_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No payments recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>

