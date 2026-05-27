@php
    $roleName = auth()->user()->role?->name;
    $greeting = match(true) {
        now()->hour < 12 => 'Good morning',
        now()->hour < 18 => 'Good afternoon',
        default => 'Good evening',
    };
    $roleLabel = match($roleName) {
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'receptionist' => 'Receptionist',
        'manager' => 'Manager',
        default => 'Staff',
    };
    $roleColor = match($roleName) {
        'super_admin' => 'bg-purple-100 text-purple-700 ring-purple-200 dark:bg-purple-900/30 dark:text-purple-200 dark:ring-purple-800',
        'admin' => 'bg-blue-100 text-blue-700 ring-blue-200 dark:bg-blue-900/30 dark:text-blue-200 dark:ring-blue-800',
        'receptionist' => 'bg-emerald-100 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-200 dark:ring-emerald-800',
        'manager' => 'bg-amber-100 text-amber-700 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-200 dark:ring-amber-800',
        default => 'bg-gray-100 text-gray-700 ring-gray-200 dark:bg-gray-900/30 dark:text-gray-200 dark:ring-gray-800',
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $greeting }}, {{ auth()->user()->name }}</h2>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $roleColor }}">{{ $roleLabel }}</span>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, F j, Y') }} &middot; Rubavu Hotel operations overview</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-ui.kpi title="Total Rooms" :value="$totalRooms" :sub="$occupancyRate.'% occupancy'" icon="building" class="animate-fade-in-up stagger-1" />
                <x-ui.kpi title="Available Rooms" :value="$availableRooms" icon="check" class="animate-fade-in-up stagger-2" />
                <x-ui.kpi title="Occupied Rooms" :value="$occupiedRooms" icon="x" class="animate-fade-in-up stagger-3" />
                <x-ui.kpi title="Revenue (MTD)" :value="\App\Support\Money::formatRwf($mtdRevenue)" icon="dollar" class="animate-fade-in-up stagger-4" />
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <x-ui.card title="Bookings (Last 14 days)">
                    <canvas id="bookingsChart" height="120"></canvas>
                </x-ui.card>

                <x-ui.card title="Revenue (Last 14 days)">
                    <canvas id="revenueChart" height="120"></canvas>
                </x-ui.card>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <x-ui.card title="Today">
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Expected check-ins</dt>
                            <dd class="font-semibold text-gray-900 dark:text-white">{{ $checkinsToday }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Reserved rooms</dt>
                            <dd class="font-semibold text-gray-900 dark:text-white">{{ $reservedRooms }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Outstanding balances</dt>
                            <dd class="font-semibold text-gray-900 dark:text-white">@rwf($outstandingBalance)</dd>
                        </div>
                    </dl>
                </x-ui.card>

                <x-ui.card title="Recent Bookings" class="lg:col-span-1">
                    <div class="space-y-3">
                        @forelse ($recentBookings as $b)
                            <a href="{{ route('bookings.show', $b) }}" class="block rounded-xl border border-black/5 p-3 hover:bg-black/5 dark:border-white/10 dark:hover:bg-white/5">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $b->customer->full_name }}</div>
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $b->booking_number }} • {{ $b->room?->room_number ?? 'No room' }}</div>
                                    </div>
                                    <div class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ str_replace('_',' ',$b->status->value) }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400">No recent bookings.</div>
                        @endforelse
                    </div>
                </x-ui.card>

                <x-ui.card title="Recent Payments" class="lg:col-span-1">
                    <div class="space-y-3">
                        @forelse ($recentPayments as $p)
                            <div class="rounded-xl border border-black/5 p-3 dark:border-white/10">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $p->booking->customer->full_name }}</div>
                                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $p->payment_reference }} • {{ optional($p->paid_at)->format('Y-m-d H:i') }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">@rwf($p->amount)</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400">No recent payments.</div>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                if (!window.Chart) return;

                const labels = @json($bookingsChart['labels']);
                const bookingsData = @json($bookingsChart['data']);
                const revenueData = @json($revenueChart['data']);

                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(148,163,184,0.15)' } },
                    },
                };

                new Chart(document.getElementById('bookingsChart'), {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{ data: bookingsData, backgroundColor: 'rgba(99,102,241,0.6)', borderRadius: 8 }],
                    },
                    options: baseOptions,
                });

                new Chart(document.getElementById('revenueChart'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{ data: revenueData, borderColor: 'rgba(217,70,239,0.85)', backgroundColor: 'rgba(217,70,239,0.12)', tension: 0.35, fill: true }],
                    },
                    options: baseOptions,
                });
            })();
        </script>
    @endpush
</x-app-layout>
