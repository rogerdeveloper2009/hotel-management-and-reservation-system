<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Amenities</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage amenities available for rooms.</p>
            </div>
            <a href="{{ route('amenities.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Add Amenity</a>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($amenities as $amenity)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $amenity->name }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('amenities.edit', $amenity) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('amenities.destroy', $amenity) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-3 text-rose-600 hover:underline" onclick="return confirm('Delete this amenity?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $amenities->links() }}</div>
    </x-ui.card>
</x-app-layout>

