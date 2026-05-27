<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Rooms</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage rooms, pricing, capacity, and status.</p>
            </div>
            <a href="{{ route('rooms.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Add Room</a>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Room</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Floor</th>
                        <th class="px-4 py-3">Capacity</th>
                        <th class="px-4 py-3">Rate</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($rooms as $room)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('rooms.show', $room) }}" class="hover:underline">{{ $room->room_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $room->roomType->name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $room->floor }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $room->capacity }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">@rwf($room->rate_per_night)</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-black/5 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-200">
                                    {{ str_replace('_', ' ', $room->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('rooms.edit', $room) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-3 text-rose-600 hover:underline" onclick="return confirm('Delete this room?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $rooms->links() }}</div>
    </x-ui.card>
</x-app-layout>

