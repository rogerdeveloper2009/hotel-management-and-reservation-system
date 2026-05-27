<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Payments</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Track cash and mobile money payments in RWF.</p>
            </div>
            <form method="GET" action="{{ route('payments.index') }}" class="flex items-center gap-2">
                <input name="q" value="{{ $q }}" placeholder="Search reference / booking / customer" class="w-80 rounded-xl border border-black/10 bg-white/70 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" />
                <button class="rounded-xl bg-black/90 px-3 py-2 text-sm font-semibold text-white hover:bg-black dark:bg-white/10 dark:hover:bg-white/15" type="submit">Search</button>
            </form>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">Booking</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Method</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Received By</th>
                        <th class="px-4 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($payments as $payment)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $payment->payment_reference }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                <a href="{{ route('bookings.show', $payment->booking) }}" class="text-indigo-600 hover:underline">{{ $payment->booking->booking_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $payment->booking->customer->full_name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $payment->method->value) }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($payment->amount)</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $payment->receiver?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ optional($payment->paid_at)->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $payments->links() }}</div>
    </x-ui.card>
</x-app-layout>

