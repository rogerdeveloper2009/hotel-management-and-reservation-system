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
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100">
        <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-fuchsia-50 dark:from-slate-950 dark:via-slate-950 dark:to-indigo-950">
            <div class="bg-grid pointer-events-none absolute inset-0 opacity-40 dark:opacity-20"></div>
            <div class="bg-dots pointer-events-none absolute inset-0 opacity-50 dark:opacity-30"></div>

            <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 animate-float rounded-full bg-gradient-to-br from-indigo-200/40 to-fuchsia-200/30 blur-3xl dark:from-indigo-800/20 dark:to-fuchsia-800/10"></div>
            <div class="pointer-events-none absolute -bottom-40 -right-40 h-[30rem] w-[30rem] animate-float-delayed rounded-full bg-gradient-to-br from-amber-200/30 to-rose-200/30 blur-3xl dark:from-amber-800/10 dark:to-rose-800/10"></div>

            <div class="relative mx-auto flex min-h-screen max-w-6xl items-center justify-center px-4 py-10">
                <div class="grid w-full grid-cols-1 gap-8 lg:grid-cols-2 lg:items-center">
                    <div class="hidden animate-fade-in-up lg:block">
                        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600 p-8 shadow-xl transition hover:shadow-2xl hover:shadow-indigo-500/20">
                            <div class="absolute -right-8 -top-8 h-40 w-40 animate-pulse-subtle rounded-full bg-white/10"></div>
                            <div class="absolute -bottom-6 -left-6 h-32 w-32 animate-float rounded-full bg-white/5"></div>
                            <div class="relative">
                                <div class="inline-flex animate-fade-in-up items-center gap-3 rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur">
                                    <img src="{{ asset('images/logo.png') }}" alt="Rubavu Hotel" class="h-10 w-10 rounded-xl object-cover">
                                    <div>
                                        <div class="text-sm font-semibold text-white">{{ config('app.name', 'Rubavu Hotel') }}</div>
                                        <div class="text-xs text-white/70">Internal Dashboard</div>
                                    </div>
                                </div>
                                <h1 class="mt-8 animate-fade-in-up text-4xl font-semibold tracking-tight text-white stagger-1">Operate your hotel with clarity.</h1>
                                <p class="mt-4 animate-fade-in-up max-w-lg text-sm leading-6 text-white/80 stagger-2">Reservations, rooms, payments, invoices, and analytics — all in one secure internal dashboard for Rubavu Hotel.</p>
                            </div>
                            <div class="mt-10 animate-fade-in-up stagger-3 flex items-center gap-6">
                                <div class="flex -space-x-2">
                                    <div class="h-8 w-8 animate-float rounded-full border-2 border-white bg-amber-300"></div>
                                    <div class="h-8 w-8 animate-float-delayed rounded-full border-2 border-white bg-blue-400"></div>
                                    <div class="h-8 w-8 animate-float rounded-full border-2 border-white bg-emerald-400"></div>
                                </div>
                                <div class="text-xs text-white/70">Trusted by 500+ hotels</div>
                            </div>
                        </div>
                        <div class="mt-6 grid animate-fade-in-up grid-cols-3 gap-4 stagger-4">
                            <div class="hover-lift rounded-2xl border border-black/5 bg-white/70 p-4 backdrop-blur transition hover:bg-white/90 dark:border-white/10 dark:bg-white/5">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">12</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Room types</div>
                            </div>
                            <div class="hover-lift rounded-2xl border border-black/5 bg-white/70 p-4 backdrop-blur transition hover:bg-white/90 dark:border-white/10 dark:bg-white/5">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">48</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total rooms</div>
                            </div>
                            <div class="hover-lift rounded-2xl border border-black/5 bg-white/70 p-4 backdrop-blur transition hover:bg-white/90 dark:border-white/10 dark:bg-white/5">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">99%</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">Uptime</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="animate-scale-in mx-auto w-full max-w-md rounded-3xl border border-white/10 bg-white/70 p-6 shadow-sm backdrop-blur transition hover:shadow-xl dark:bg-white/5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="animate-fade-in-down text-sm font-semibold text-gray-900 dark:text-white">Sign in</div>
                                    <div class="animate-fade-in-down stagger-1 text-xs text-gray-500 dark:text-gray-400">Use your staff username</div>
                                </div>
                                <button
                                    class="rounded-lg p-2 text-gray-600 transition hover:bg-black/5 hover:scale-110 dark:text-gray-300 dark:hover:bg-white/10"
                                    x-data
                                    @click="
                                        document.documentElement.classList.toggle('dark');
                                        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                                    "
                                    title="Toggle theme"
                                    type="button"
                                >
                                    <svg class="h-5 w-5 transition hover:rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0l-1.414-1.414M7.05 7.05 5.636 5.636"/>
                                        <circle cx="12" cy="12" r="4"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-6 animate-fade-in-up stagger-2">
                                {{ $slot }}
                            </div>
                        </div>
                        <div class="mt-4 animate-fade-in-up stagger-3 text-center text-xs text-gray-500 dark:text-gray-400">This system is for internal hotel operations only.</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
