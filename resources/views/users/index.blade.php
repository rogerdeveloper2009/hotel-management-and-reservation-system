<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Staff Users</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage internal system users and roles.</p>
            </div>
            <a href="{{ route('users.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Add User</a>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($users as $u)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $u->name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $u->username }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $u->role?->label ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('users.edit', $u) }}" class="text-indigo-600 hover:underline">Edit</a>
                                @if (auth()->id() !== $u->id)
                                    <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-3 text-rose-600 hover:underline" onclick="return confirm('Delete this user?')">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </x-ui.card>
</x-app-layout>

