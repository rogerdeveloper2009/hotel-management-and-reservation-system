<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Add Amenity</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Create a new amenity (e.g., WiFi, TV, Breakfast).</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('amenities.store') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('amenities.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Save</button>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>

