@php
    $u = $user;
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $u?->name)" required />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="username" value="Username" />
        <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $u?->username)" required />
        <x-input-error class="mt-2" :messages="$errors->get('username')" />
    </div>

    <div>
        <x-input-label for="email" value="Email (optional)" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $u?->email)" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="role_id" value="Role" />
        <select id="role_id" name="role_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" required>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id', $u?->role_id) == $role->id)>{{ $role->label }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="password" :value="$u ? 'New Password (leave blank to keep)' : 'Password'" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
        <x-input-error class="mt-2" :messages="$errors->get('password')" />
    </div>
</div>

