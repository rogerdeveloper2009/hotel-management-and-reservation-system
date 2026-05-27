@props([
    'title',
    'value' => '—',
    'sub' => null,
    'icon' => null,
])

@php
    $icons = [
        'building' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18M6 21V7a2 2 0 012-2h8a2 2 0 012 2v14M9 7h1m-1 4h1m-1 4h1m4-8h1m-1 4h1m-1 4h1"/></svg>',
        'check' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'x' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/></svg>',
        'dollar' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>',
    ];
    $iconSvg = $icons[$icon] ?? '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>';
    $gradients = [
        'building' => 'from-indigo-500/20 to-blue-500/20',
        'check' => 'from-emerald-500/20 to-teal-500/20',
        'x' => 'from-rose-500/20 to-pink-500/20',
        'dollar' => 'from-amber-500/20 to-orange-500/20',
    ];
    $gradient = $gradients[$icon] ?? 'from-indigo-500/20 to-fuchsia-500/20';
@endphp

<div {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-2xl border border-white/10 bg-white/70 p-5 shadow-sm backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-500/5 dark:bg-white/5']) }}>
    <div class="flex items-start justify-between gap-3">
        <div>
            <div class="text-xs font-medium tracking-wide text-gray-500 dark:text-gray-400">{{ $title }}</div>
            <div class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</div>
            @if ($sub)
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $sub }}</div>
            @endif
        </div>
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br {{ $gradient }} ring-1 ring-black/5 dark:ring-white/10">
            {!! $iconSvg !!}
        </div>
    </div>
</div>

