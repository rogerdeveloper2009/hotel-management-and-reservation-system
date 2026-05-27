<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Add Room Type</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Create a new room category.</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('room-types.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="default_capacity" value="Default Capacity" />
                    <x-text-input id="default_capacity" name="default_capacity" type="number" class="mt-1 block w-full" :value="old('default_capacity', 1)" min="1" required />
                    <x-input-error class="mt-2" :messages="$errors->get('default_capacity')" />
                </div>
            </div>

            <div>
                <x-input-label for="default_rate" value="Default Rate (RWF)" />
                <x-text-input id="default_rate" name="default_rate" type="number" step="0.01" class="mt-1 block w-full" :value="old('default_rate', 0)" min="0" required />
                <x-input-error class="mt-2" :messages="$errors->get('default_rate')" />
            </div>

            <div>
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">{{ old('description') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('room-types.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Save</button>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>

