@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'rounded-md border-gray-300 bg-white/70 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-gray-400',
    ]) }}
/>
