<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomStoreRequest;
use App\Http\Requests\RoomUpdateRequest;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        return view('rooms.index', [
            'rooms' => Room::query()
                ->with(['roomType'])
                ->orderBy('room_number')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('rooms.create', [
            'roomTypes' => RoomType::query()->orderBy('name')->get(),
            'amenities' => Amenity::query()->orderBy('name')->get(),
        ]);
    }

    public function store(RoomStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $amenityIds = $data['amenity_ids'] ?? [];
        unset($data['amenity_ids']);

        $room = null;

        DB::transaction(function () use ($data, $amenityIds, $request, &$room) {
            $room = Room::create($data);
            $room->amenities()->sync($amenityIds);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('rooms', 'public');
                    $room->images()->create([
                        'path' => $path,
                        'caption' => null,
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        app(ActivityLogger::class)->log('room.create', $room, "Created room {$room->room_number}");

        return redirect()->route('rooms.index')->with('success', 'Room created.');
    }

    public function show(Room $room): View
    {
        $room->load(['roomType', 'amenities', 'images']);

        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        $room->load(['amenities', 'images']);

        return view('rooms.edit', [
            'room' => $room,
            'roomTypes' => RoomType::query()->orderBy('name')->get(),
            'amenities' => Amenity::query()->orderBy('name')->get(),
        ]);
    }

    public function update(RoomUpdateRequest $request, Room $room): RedirectResponse
    {
        $data = $request->validated();
        $amenityIds = $data['amenity_ids'] ?? [];
        unset($data['amenity_ids']);

        DB::transaction(function () use ($data, $amenityIds, $request, $room) {
            $room->update($data);
            $room->amenities()->sync($amenityIds);

            if ($request->hasFile('images')) {
                $currentCount = $room->images()->count();
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('rooms', 'public');
                    $room->images()->create([
                        'path' => $path,
                        'caption' => null,
                        'sort_order' => $currentCount + $index,
                    ]);
                }
            }
        });

        app(ActivityLogger::class)->log('room.update', $room, "Updated room {$room->room_number}");

        return redirect()->route('rooms.show', $room)->with('success', 'Room updated.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        DB::transaction(function () use ($room) {
            foreach ($room->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            $room->amenities()->detach();
            $room->delete();
        });

        app(ActivityLogger::class)->log('room.delete', $room, "Deleted room {$room->room_number}");

        return redirect()->route('rooms.index')->with('success', 'Room deleted.');
    }
}
