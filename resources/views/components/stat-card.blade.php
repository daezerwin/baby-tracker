@props(['label', 'value', 'meta' => null, 'color' => 'blue', 'icon' => null, 'link' => null, 'linkLabel' => 'View all'])

@php
    $colors = [
        'blue' => 'bg-blue-50 text-blue-700',
        'gold' => 'bg-amber-100 text-amber-700',
        'amber' => 'bg-amber-50 text-amber-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'violet' => 'bg-violet-50 text-violet-600',
    ][$color] ?? 'bg-blue-50 text-blue-700';
@endphp

<x-card class="p-6 flex items-start gap-4">
    @if ($icon)
        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $colors }}">
            {{ $icon }}
        </div>
    @endif
    <div class="min-w-0">
        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">{{ $label }}</p>
        <p class="text-2xl font-semibold text-gray-800 truncate mt-0.5">{{ $value }}</p>
        @if ($meta)
            <p class="text-sm text-gray-400 mt-1">{{ $meta }}</p>
        @endif
        @if ($link)
            <a href="{{ $link }}" wire:navigate class="text-sm text-blue-600 hover:text-blue-700 mt-1 inline-block">{{ $linkLabel }} →</a>
        @endif
    </div>
</x-card>
