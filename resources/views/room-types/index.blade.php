<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Room Types</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage your room categories and default pricing.</p>
            </div>
            <a href="{{ route('room-types.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Add Room Type</a>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Default Rate</th>
                        <th class="px-4 py-3">Capacity</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($roomTypes as $type)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $type->name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($type->default_rate)</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $type->default_capacity }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('room-types.edit', $type) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('room-types.destroy', $type) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-3 text-rose-600 hover:underline" onclick="return confirm('Delete this room type?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $roomTypes->links() }}</div>
    </x-ui.card>
</x-app-layout>

