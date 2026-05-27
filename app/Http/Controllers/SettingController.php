<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsUpdateRequest;
use App\Models\Setting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::query()->pluck('value', 'key')->all();

        return view('settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(SettingsUpdateRequest $request): RedirectResponse
    {
        foreach ($request->validated() as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value === '' ? null : (string) $value]);
        }

        app(ActivityLogger::class)->log('settings.update', description: 'Updated system settings');

        return redirect()->route('settings.index')->with('success', 'Settings updated.');
    }
}

