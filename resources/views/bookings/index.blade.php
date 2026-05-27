<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Reservations</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Create, track, and manage bookings.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('bookings.index') }}" class="flex items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Search booking / customer / room" class="w-72 rounded-xl border border-black/10 bg-white/70 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" />
                    <select name="status" class="rounded-xl border border-black/10 bg-white/70 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected($status === $s)>{{ str_replace('_', ' ', $s) }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-xl bg-black/90 px-3 py-2 text-sm font-semibold text-white hover:bg-black dark:bg-white/10 dark:hover:bg-white/15" type="submit">Filter</button>
                </form>
                <a href="{{ route('bookings.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">New Reservation</a>
            </div>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Booking</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Room</th>
                        <th class="px-4 py-3">Dates</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($bookings as $booking)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('bookings.show', $booking) }}" class="hover:underline">{{ $booking->booking_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $booking->customer->full_name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                {{ $booking->room?->room_number ?? '—' }}
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $booking->roomType->name }})</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                {{ $booking->check_in_date->format('Y-m-d') }} → {{ $booking->check_out_date->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $booking->status->value) }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($booking->total_amount)</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($booking->balance_amount)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $bookings->links() }}</div>
    </x-ui.card>
</x-app-layout>

