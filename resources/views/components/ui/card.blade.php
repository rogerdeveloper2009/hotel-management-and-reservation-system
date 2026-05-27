@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'hover-lift relative overflow-hidden rounded-2xl border border-white/10 bg-white/70 shadow-sm backdrop-blur dark:border-white/10 dark:bg-white/5']) }}>
    @if ($title)
        <div class="flex items-center justify-between border-b border-black/5 px-5 py-4 dark:border-white/10">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            {{ $actions ?? '' }}
        </div>
    @endif
    <div class="p-5">
        {{ $slot }}
    </div>
</div>

