<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Room</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Room {{ $room->room_number }}</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="room_number" value="Room Number" />
                    <x-text-input id="room_number" name="room_number" type="text" class="mt-1 block w-full" :value="old('room_number', $room->room_number)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('room_number')" />
                </div>
                <div>
                    <x-input-label for="room_type_id" value="Room Type" />
                    <select id="room_type_id" name="room_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
                        @foreach ($roomTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('room_type_id', $room->room_type_id) == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('room_type_id')" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <x-input-label for="floor" value="Floor" />
                    <x-text-input id="floor" name="floor" type="number" class="mt-1 block w-full" :value="old('floor', $room->floor)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('floor')" />
                </div>
                <div>
                    <x-input-label for="capacity" value="Capacity" />
                    <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full" :value="old('capacity', $room->capacity)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
                        @foreach (['available','reserved','occupied','cleaning','maintenance'] as $s)
                            <option value="{{ $s }}" @selected(old('status', $room->status->value) === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>
            </div>

            <div>
                <x-input-label for="rate_per_night" value="Rate per Night (RWF)" />
                <x-text-input id="rate_per_night" name="rate_per_night" type="number" step="0.01" class="mt-1 block w-full" :value="old('rate_per_night', $room->rate_per_night)" required />
                <x-input-error class="mt-2" :messages="$errors->get('rate_per_night')" />
            </div>

            <div>
                <x-input-label for="amenity_ids" value="Amenities" />
                <select id="amenity_ids" name="amenity_ids[]" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">
                    @foreach ($amenities as $amenity)
                        <option value="{{ $amenity->id }}" @selected(collect(old('amenity_ids', $room->amenities->pluck('id')->all()))->contains($amenity->id))>{{ $amenity->name }}</option>
                    @endforeach
                </select>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/⌘ to select multiple.</div>
                <x-input-error class="mt-2" :messages="$errors->get('amenity_ids')" />
            </div>

            <div>
                <x-input-label for="images" value="Add More Images" />
                <input id="images" name="images[]" type="file" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-indigo-500 dark:text-gray-200" />
                <x-input-error class="mt-2" :messages="$errors->get('images')" />
                @if ($room->images->count())
                    <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($room->images as $img)
                            <div class="aspect-video overflow-hidden rounded-xl border border-black/5 bg-black/5 dark:border-white/10">
                                <img src="{{ asset('storage/'.$img->path) }}" alt="" class="h-full w-full object-cover" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="notes" value="Notes" />
                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5">{{ old('notes', $room->notes) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('rooms.show', $room) }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Update</button>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>

