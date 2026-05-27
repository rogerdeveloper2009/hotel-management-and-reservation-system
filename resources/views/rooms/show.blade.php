<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Room {{ $room->room_number }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $room->roomType->name }} • Floor {{ $room->floor }} • Capacity {{ $room->capacity }}</p>
            </div>
            <a href="{{ route('rooms.edit', $room) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Edit Room</a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-ui.card class="lg:col-span-2" title="Details">
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ ucfirst($room->status->value) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Rate per night</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">@rwf($room->rate_per_night)</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Amenities</dt>
                    <dd class="mt-1 flex flex-wrap gap-2">
                        @forelse ($room->amenities as $amenity)
                            <span class="rounded-full bg-black/5 px-3 py-1 text-xs text-gray-700 dark:bg-white/10 dark:text-gray-200">{{ $amenity->name }}</span>
                        @empty
                            <span class="text-sm text-gray-500 dark:text-gray-400">No amenities assigned.</span>
                        @endforelse
                    </dd>
                </div>
                @if ($room->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-700 dark:text-gray-200">{{ $room->notes }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        <x-ui.card title="Images">
            @if ($room->images->count())
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($room->images as $img)
                        <div class="aspect-video overflow-hidden rounded-xl border border-black/5 bg-black/5 dark:border-white/10">
                            <img src="{{ asset('storage/'.$img->path) }}" alt="" class="h-full w-full object-cover" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-gray-500 dark:text-gray-400">No images uploaded.</div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>

