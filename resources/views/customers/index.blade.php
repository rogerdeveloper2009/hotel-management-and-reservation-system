<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Customers</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Guest profiles and booking history.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('customers.index') }}" class="flex items-center gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Search name / phone / ID" class="w-64 rounded-xl border border-black/10 bg-white/70 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5" />
                    <button class="rounded-xl bg-black/90 px-3 py-2 text-sm font-semibold text-white hover:bg-black dark:bg-white/10 dark:hover:bg-white/15" type="submit">Search</button>
                </form>
                <a href="{{ route('customers.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Add Customer</a>
            </div>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black/5 text-sm dark:divide-white/10">
                <thead class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Nationality</th>
                        <th class="px-4 py-3">Bookings</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($customers as $customer)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('customers.show', $customer) }}" class="hover:underline">{{ $customer->full_name }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $customer->phone_number ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $customer->nationality ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $customer->bookings_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('customers.edit', $customer) }}" class="text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-3 text-rose-600 hover:underline" onclick="return confirm('Delete this customer?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $customers->links() }}</div>
    </x-ui.card>
</x-app-layout>

