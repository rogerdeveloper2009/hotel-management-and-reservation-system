<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomTypeStoreRequest;
use App\Http\Requests\RoomTypeUpdateRequest;
use App\Models\RoomType;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RoomTypeController extends Controller
{
    public function index(): View
    {
        return view('room-types.index', [
            'roomTypes' => RoomType::query()->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('room-types.create');
    }

    public function store(RoomTypeStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $roomType = RoomType::create($data);
        app(ActivityLogger::class)->log('room_type.create', $roomType, "Created room type {$roomType->name}");

        return redirect()->route('room-types.index')->with('success', 'Room type created.');
    }

    public function edit(RoomType $roomType): View
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(RoomTypeUpdateRequest $request, RoomType $roomType): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $roomType->update($data);
        app(ActivityLogger::class)->log('room_type.update', $roomType, "Updated room type {$roomType->name}");

        return redirect()->route('room-types.index')->with('success', 'Room type updated.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        $name = $roomType->name;
        $roomType->delete();
        app(ActivityLogger::class)->log('room_type.delete', $roomType, "Deleted room type {$name}");

        return redirect()->route('room-types.index')->with('success', 'Room type deleted.');
    }
}

