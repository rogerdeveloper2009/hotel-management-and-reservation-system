<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">New Reservation</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Create a booking and prevent double booking automatically.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
            @csrf

            @include('bookings.partials.form-fields', [
                'booking' => null,
                'customers' => $customers,
                'roomTypes' => $roomTypes,
                'rooms' => $rooms,
                'defaultTaxRate' => $defaultTaxRate,
            ])

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('bookings.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Create</button>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>

