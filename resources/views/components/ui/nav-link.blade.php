@props([
    'active' => false,
    'icon' => null,
])

@php
    $base = 'group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition';
    $activeClass = $active
        ? 'bg-gradient-to-r from-indigo-500/15 to-fuchsia-500/10 text-gray-900 ring-1 ring-black/5 dark:text-white dark:ring-white/10'
        : 'text-gray-700 transition-all duration-200 hover:bg-black/5 hover:scale-[1.02] dark:text-gray-200 dark:hover:bg-white/10';
@endphp

<a {{ $attributes->merge(['class' => $base.' '.$activeClass]) }}>
    <span class="grid h-9 w-9 place-items-center rounded-xl bg-black/5 text-gray-700 dark:bg-white/10 dark:text-gray-200">
        @switch($icon)
            @case('grid')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>
                @break
            @case('door')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3h8v18H8z"/><path d="M16 21h2"/><path d="M10 12h.01"/></svg>
                @break
            @case('users')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                @break
            @case('calendar')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                @break
            @case('credit-card')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                @break
            @case('sparkles')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l1.2 4.2L18 8l-4.8 1.8L12 14l-1.2-4.2L6 8l4.8-1.8L12 2z"/><path d="M19 14l.7 2.3L22 17l-2.3.7L19 20l-.7-2.3L16 17l2.3-.7L19 14z"/></svg>
                @break
            @case('chart')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 6-6"/></svg>
                @break
            @case('settings')
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/><path d="M19.4 15a7.8 7.8 0 0 0 .1-2l2-1.2-2-3.5-2.3.7a8 8 0 0 0-1.7-1L15.2 4h-4.4L10.5 7a8 8 0 0 0-1.7 1L6.5 7.3l-2 3.5 2 1.2a7.8 7.8 0 0 0 .1 2l-2 1.2 2 3.5 2.3-.7a8 8 0 0 0 1.7 1l.3 3h4.4l.3-3a8 8 0 0 0 1.7-1l2.3.7 2-3.5-2-1.2z"/></svg>
                @break
            @default
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        @endswitch
    </span>
    <span class="truncate">{{ $slot }}</span>
</a>

