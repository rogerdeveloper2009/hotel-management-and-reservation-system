<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">System configuration (internal use only).</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="hotel_name" value="Hotel Name" />
                <x-text-input id="hotel_name" name="hotel_name" type="text" class="mt-1 block w-full" :value="old('hotel_name', $settings['hotel_name'] ?? config('app.name'))" />
                <x-input-error class="mt-2" :messages="$errors->get('hotel_name')" />
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="default_tax_rate" value="Default Tax Rate (%)" />
                    <x-text-input id="default_tax_rate" name="default_tax_rate" type="number" step="0.01" class="mt-1 block w-full" :value="old('default_tax_rate', $settings['default_tax_rate'] ?? 18)" />
                    <x-input-error class="mt-2" :messages="$errors->get('default_tax_rate')" />
                </div>
                <div>
                    <x-input-label for="currency_code" value="Currency Code" />
                    <x-text-input id="currency_code" name="currency_code" type="text" class="mt-1 block w-full" :value="old('currency_code', $settings['currency_code'] ?? 'RWF')" />
                    <x-input-error class="mt-2" :messages="$errors->get('currency_code')" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Save</button>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>

