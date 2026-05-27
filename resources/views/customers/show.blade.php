<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $customer->full_name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Customer profile</p>
            </div>
            <a href="{{ route('bookings.create', ['customer_id' => $customer->id]) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">New Reservation</a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-ui.card title="Details" class="lg:col-span-1">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $customer->phone_number ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Nationality</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $customer->nationality ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Passport/ID</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $customer->passport_or_id ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Address</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $customer->address ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Emergency Contact</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">
                        {{ $customer->emergency_contact_name ?? '—' }}
                        @if($customer->emergency_contact_phone)
                            <span class="text-gray-500 dark:text-gray-400">({{ $customer->emergency_contact_phone }})</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="Recent Bookings" class="lg:col-span-2">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                    <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Booking</th>
                            <th class="px-4 py-3">Dates</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse ($customer->bookings as $booking)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                    <a class="hover:underline" href="{{ route('bookings.show', $booking) }}">{{ $booking->booking_number }}</a>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                    {{ $booking->check_in_date->format('Y-m-d') }} → {{ $booking->check_out_date->format('Y-m-d') }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ str_replace('_', ' ', $booking->status->value) }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($booking->total_amount)</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No bookings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>

