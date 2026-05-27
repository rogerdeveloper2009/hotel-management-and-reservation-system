<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmenityStoreRequest;
use App\Http\Requests\AmenityUpdateRequest;
use App\Models\Amenity;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AmenityController extends Controller
{
    public function index(): View
    {
        return view('amenities.index', [
            'amenities' => Amenity::query()->orderBy('name')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('amenities.create');
    }

    public function store(AmenityStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $amenity = Amenity::create($data);
        app(ActivityLogger::class)->log('amenity.create', $amenity, "Created amenity {$amenity->name}");

        return redirect()->route('amenities.index')->with('success', 'Amenity created.');
    }

    public function edit(Amenity $amenity): View
    {
        return view('amenities.edit', compact('amenity'));
    }

    public function update(AmenityUpdateRequest $request, Amenity $amenity): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $amenity->update($data);
        app(ActivityLogger::class)->log('amenity.update', $amenity, "Updated amenity {$amenity->name}");

        return redirect()->route('amenities.index')->with('success', 'Amenity updated.');
    }

    public function destroy(Amenity $amenity): RedirectResponse
    {
        $name = $amenity->name;
        $amenity->delete();
        app(ActivityLogger::class)->log('amenity.delete', $amenity, "Deleted amenity {$name}");

        return redirect()->route('amenities.index')->with('success', 'Amenity deleted.');
    }
}

