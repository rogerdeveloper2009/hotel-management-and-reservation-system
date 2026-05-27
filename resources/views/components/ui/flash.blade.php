@php
    $success = session('success');
    $error = session('error');
@endphp

<div class="space-y-3">
    @if ($success)
        <div x-data="{ open: true }" x-show="open" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-100">
            <div class="flex items-start justify-between gap-3">
                <div class="text-sm">{{ $success }}</div>
                <button type="button" class="rounded-lg p-1 text-emerald-700 hover:bg-emerald-100 dark:text-emerald-200 dark:hover:bg-white/10" @click="open = false">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    @if ($error)
        <div x-data="{ open: true }" x-show="open" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 shadow-sm dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-100">
            <div class="flex items-start justify-between gap-3">
                <div class="text-sm">{{ $error }}</div>
                <button type="button" class="rounded-lg p-1 text-rose-700 hover:bg-rose-100 dark:text-rose-200 dark:hover:bg-white/10" @click="open = false">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif
</div>

