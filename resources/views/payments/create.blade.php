<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Record Payment</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">All amounts are in Rwandan Francs (RWF).</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="booking_id" value="Booking" />
                <select id="booking_id" name="booking_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
                    @foreach ($bookings as $b)
                        <option value="{{ $b->id }}" @selected(old('booking_id', $booking?->id) == $b->id)>{{ $b->booking_number }} — {{ $b->customer->full_name }} (Balance: @rwf($b->balance_amount))</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('booking_id')" />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="amount" value="Amount (RWF)" />
                    <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                </div>

                <div>
                    <x-input-label for="method" value="Payment Method" />
                    <select id="method" name="method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
                        @foreach ($methods as $m)
                            <option value="{{ $m }}" @selected(old('method', 'cash') === $m)>{{ str_replace('_', ' ', $m) }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('method')" />
                </div>
            </div>

            <div>
                <x-input-label for="notes" value="Notes" />
                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">{{ old('notes') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ $booking ? route('bookings.show', $booking) : route('payments.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Record</button>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>
