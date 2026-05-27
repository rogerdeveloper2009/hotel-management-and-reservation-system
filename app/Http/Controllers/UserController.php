<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::query()->with('role')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::query()->orderBy('label')->get(),
        ]);
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        app(ActivityLogger::class)->log('user.create', $user, "Created user {$user->username}");

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', [
            'user' => $user->load('role'),
            'roles' => Role::query()->orderBy('label')->get(),
        ]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        app(ActivityLogger::class)->log('user.update', $user, "Updated user {$user->username}");

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === request()->user()?->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $username = $user->username;
        $user->delete();
        app(ActivityLogger::class)->log('user.delete', $user, "Deleted user {$username}");

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }
}

