<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Reports & Analytics</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daily revenue, occupancy, ADR and RevPAR.</p>
            </div>
            <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date->toDateString() }}" class="rounded-xl border border-black/10 bg-white/70 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" />
                <button class="rounded-xl bg-black/90 px-3 py-2 text-sm font-semibold text-white hover:bg-black dark:bg-white/10 dark:hover:bg-white/15" type="submit">Apply</button>
            </form>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.kpi title="Total Rooms" :value="$totalRooms" />
        <x-ui.kpi title="Occupied Rooms" :value="$occupiedRooms" :sub="$occupancyRate.'% occupancy'" />
        <x-ui.kpi title="Daily Payments" :value="\App\Support\Money::formatRwf($dailyRevenue)" />
        <x-ui.kpi title="Outstanding Balance" :value="\App\Support\Money::formatRwf($outstandingBalance)" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-ui.card title="KPIs" class="lg:col-span-1">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Occupancy Rate</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $occupancyRate }}%</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">ADR</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($adr)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">RevPAR</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($revpar)</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Room Revenue (Day)</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">@rwf($roomRevenueForDay)</dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('reports.daily.pdf', ['date' => $date->toDateString()]) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Export PDF</a>
                <a href="{{ route('reports.daily.xlsx', ['date' => $date->toDateString()]) }}" class="rounded-xl bg-black/90 px-4 py-2 text-sm font-semibold text-white hover:bg-black dark:bg-white/10 dark:hover:bg-white/15">Export Excel</a>
            </div>
        </x-ui.card>

        <x-ui.card title="Daily Payments" class="lg:col-span-2">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                    <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Reference</th>
                            <th class="px-4 py-3">Booking</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Method</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse ($dailyPayments as $p)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $p->payment_reference }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $p->booking->booking_number }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $p->booking->customer->full_name }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $p->method->value) }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($p->amount)</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ optional($p->paid_at)->format('H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No payments recorded on this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>

