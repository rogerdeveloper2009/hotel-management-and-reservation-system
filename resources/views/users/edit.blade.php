<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Edit User</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->username }}</p>
        </div>
    </x-slot>

    <x-ui.card>
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('users.partials.form-fields', ['user' => $user, 'roles' => $roles])
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Update</button>
            </div>
        </form>
    </x-ui.card>
</x-app-layout>

