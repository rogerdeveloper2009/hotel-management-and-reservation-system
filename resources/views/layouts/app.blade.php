<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rubavu Hotel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Assets -->
        @if (file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
            <script defer src="{{ asset('assets/vendor/alpine.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/chart.umd.js') }}"></script>
        @endif

        <script>
            (() => {
                try {
                    const theme = localStorage.getItem('theme');
                    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    }
                } catch (e) {}
            })();
        </script>
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100" x-data="{ sidebarOpen: false }">
        <div class="relative min-h-screen bg-gradient-to-br from-indigo-50/40 via-white to-fuchsia-50/40 dark:from-slate-950 dark:via-slate-950 dark:to-indigo-950">
            <div class="bg-grid pointer-events-none fixed inset-0 opacity-30 dark:opacity-10"></div>
            <div class="bg-dots pointer-events-none fixed inset-0 opacity-40 dark:opacity-20"></div>

            <div class="pointer-events-none fixed -left-40 -top-40 h-96 w-96 animate-float rounded-full bg-gradient-to-br from-indigo-200/20 to-fuchsia-200/20 blur-3xl dark:from-indigo-800/10 dark:to-fuchsia-800/5"></div>
            <div class="pointer-events-none fixed -bottom-40 -right-40 h-[30rem] w-[30rem] animate-float-delayed rounded-full bg-gradient-to-br from-amber-200/20 to-rose-200/20 blur-3xl dark:from-amber-800/5 dark:to-rose-800/5"></div>

            <div class="relative flex">
                <!-- Mobile sidebar backdrop -->
                <div
                    x-show="sidebarOpen"
                    x-transition.opacity
                    class="fixed inset-0 z-40 bg-black/40 lg:hidden"
                    @click="sidebarOpen = false"
                    style="display: none"
                ></div>

                <!-- Sidebar -->
                <aside
                    class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-black/5 bg-white/70 backdrop-blur dark:border-white/10 dark:bg-white/5 lg:static lg:translate-x-0 lg:animate-slide-in"
                    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
                    x-transition
                >
                    <div class="flex h-16 items-center justify-between px-5">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Rubavu Hotel" class="h-9 w-9 rounded-xl object-cover">
                            <div>
                                <div class="text-sm font-semibold leading-4 text-gray-900 dark:text-white">{{ config('app.name', 'Rubavu Hotel') }}</div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">Internal Dashboard</div>
                            </div>
                        </a>
                        <button class="lg:hidden rounded-lg p-2 text-gray-500 hover:bg-black/5 dark:hover:bg-white/10" @click="sidebarOpen = false">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    @php
                        $roleName = auth()->user()->role?->name;
                        $canManageInventory = in_array($roleName, ['super_admin', 'admin'], true);
                        $canOperateFrontDesk = in_array($roleName, ['super_admin', 'admin', 'receptionist', 'manager'], true);
                        $roleBadge = match($roleName) {
                            'super_admin' => ['Super Admin', 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-200'],
                            'admin' => ['Admin', 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200'],
                            'receptionist' => ['Reception', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200'],
                            'manager' => ['Manager', 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200'],
                            default => ['Staff', 'bg-gray-100 text-gray-700 dark:bg-gray-900/40 dark:text-gray-200'],
                        };
                    @endphp
                    <nav class="px-3 pb-5">

                        <div class="px-3 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Operations</div>
                        <x-ui.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="grid">Dashboard</x-ui.nav-link>
                        @if ($canManageInventory)
                            <x-ui.nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')" icon="door">Rooms</x-ui.nav-link>
                            <x-ui.nav-link :href="route('room-types.index')" :active="request()->routeIs('room-types.*')" icon="door">Room Types</x-ui.nav-link>
                            <x-ui.nav-link :href="route('amenities.index')" :active="request()->routeIs('amenities.*')" icon="sparkles">Amenities</x-ui.nav-link>
                        @endif
                        @if ($canOperateFrontDesk)
                            <x-ui.nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" icon="users">Customers</x-ui.nav-link>
                            <x-ui.nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')" icon="calendar">Reservations</x-ui.nav-link>
                            <x-ui.nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" icon="credit-card">Payments</x-ui.nav-link>
                        @endif

                        <div class="mt-4 px-3 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Insights</div>
                        <x-ui.nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" icon="chart">Reports</x-ui.nav-link>
                        @if ($canManageInventory)
                            <x-ui.nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" icon="settings">Settings</x-ui.nav-link>
                        @endif
                        @if ($roleName === 'super_admin')
                            <x-ui.nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="users">Users</x-ui.nav-link>
                        @endif
                    </nav>

                    <div class="mt-auto border-t border-black/5 p-4 dark:border-white/10">
                        <div class="flex items-center gap-3 rounded-xl bg-black/5 p-3 dark:bg-white/5">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-indigo-500/40 to-fuchsia-500/40 ring-1 ring-black/5 dark:ring-white/10"></div>
                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $roleBadge[0] }}</div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Main -->
                <div class="flex min-w-0 flex-1 flex-col">
                    <!-- Topbar -->
                    <header class="animate-fade-in-down sticky top-0 z-30 border-b border-black/5 bg-white/60 backdrop-blur dark:border-white/10 dark:bg-slate-950/60">
                        <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                            <button class="rounded-lg p-2 text-gray-600 hover:bg-black/5 dark:text-gray-300 dark:hover:bg-white/10 lg:hidden" @click="sidebarOpen = true">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>

                            <div class="min-w-0 flex-1">
                                @isset($header)
                                    {{ $header }}
                                @endisset
                            </div>

                            <button
                                class="rounded-lg p-2 text-gray-600 hover:bg-black/5 dark:text-gray-300 dark:hover:bg-white/10"
                                x-data
                                @click="
                                    document.documentElement.classList.toggle('dark');
                                    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                                "
                                title="Toggle theme"
                                type="button"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0l-1.414-1.414M7.05 7.05 5.636 5.636"/>
                                    <circle cx="12" cy="12" r="4"/>
                                </svg>
                            </button>

                            <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                                <button class="flex items-center gap-2 rounded-xl border border-black/5 bg-white/70 px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-white dark:border-white/10 dark:bg-white/5 dark:text-gray-200" @click="open = !open" type="button">
                                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-indigo-500/40 to-fuchsia-500/40 ring-1 ring-black/5 dark:ring-white/10"></div>
                                    <div class="hidden sm:block text-left leading-tight">
                                        <div class="flex items-center gap-2">
                                            <span class="max-w-[8rem] truncate">{{ auth()->user()->name }}</span>
                                            <span class="rounded-md px-1.5 py-0.5 text-[10px] font-medium leading-none {{ $roleBadge[1] }}">{{ $roleBadge[0] }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ auth()->user()->username }}</div>
                                    </div>
                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>

                                <div
                                    x-show="open"
                                    x-transition.origin.top.right
                                    @click.outside="open = false"
                                    class="absolute right-0 mt-2 w-56 overflow-hidden rounded-xl border border-black/5 bg-white shadow-lg dark:border-white/10 dark:bg-slate-950"
                                    style="display: none"
                                >
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-black/5 dark:text-gray-200 dark:hover:bg-white/10">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-black/5 dark:text-gray-200 dark:hover:bg-white/10">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Content -->
                    <main class="flex-1">
                        <div class="animate-fade-in-up px-4 py-6 sm:px-6 lg:px-8">
                            <x-ui.flash />
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
