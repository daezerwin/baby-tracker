<?php

use App\Services\WeightReference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;
use function Livewire\Volt\{state, computed, mount, layout};

layout('layouts.app');

state([
    'babyId' => null,
    'quickFeedType' => 'bottle',
    'quickFeedAt' => null,
    'quickFeedAmount' => null,
    'quickFeedSide' => null,
    'quickDiaperIsWet' => true,
    'quickDiaperIsDirty' => false,
    'quickDiaperAt' => null,
    'quickDiaperConsistency' => null,
]);

mount(function () {
    $this->babyId = Auth::user()->currentBaby()?->id;
    $this->resetQuickForms();
});

$babies = computed(fn () => Auth::user()->babies()->oldest()->get());

$baby = computed(function () {
    return $this->babyId ? Auth::user()->babies()->find($this->babyId) : null;
});

$age = computed(fn () => $this->baby?->age());
$lastFeed = computed(fn () => $this->baby?->feedEntries()->first());
$lastDiaper = computed(fn () => $this->baby?->diaperEntries()->first());
$photos = computed(fn () => $this->baby?->photos);

$weightChart = computed(function () {
    if (! $this->baby) {
        return collect();
    }

    $sex = in_array($this->baby->sex, ['male', 'female'], true) ? $this->baby->sex : 'other';
    $dob = $this->baby->date_of_birth;

    return $this->baby->weightEntries()->orderBy('measured_at')->get()->map(function ($entry) use ($dob, $sex) {
        $ageInMonths = $dob->diffInDays($entry->measured_at) / 30.4375;

        return [
            'label' => $entry->measured_at->format('M j'),
            'actual' => (float) $entry->weight_kg,
            'median' => WeightReference::medianForAge($ageInMonths, $sex),
        ];
    })->values();
});

$weightStatus = computed(function () {
    if (! $this->baby) {
        return null;
    }

    $latest = $this->baby->weightEntries()->latest('measured_at')->first();

    if (! $latest) {
        return null;
    }

    $sex = in_array($this->baby->sex, ['male', 'female'], true) ? $this->baby->sex : 'other';
    $ageInMonths = $this->baby->date_of_birth->diffInDays($latest->measured_at) / 30.4375;

    return WeightReference::classify((float) $latest->weight_kg, $ageInMonths, $sex);
});

$diaperTrend = computed(function () {
    if (! $this->baby) {
        return collect();
    }

    $entries = $this->baby->diaperEntries()->where('occurred_at', '>=', now()->subDays(6)->startOfDay())->get();

    return collect(range(6, 0))->map(function ($daysAgo) use ($entries) {
        $day = now()->subDays($daysAgo);
        $dayEntries = $entries->filter(fn ($entry) => $entry->occurred_at->isSameDay($day));

        return [
            'label' => $day->format('D'),
            'pee' => $dayEntries->where('is_wet', true)->count(),
            'poop' => $dayEntries->where('is_dirty', true)->count(),
        ];
    })->values();
});

$feedTrend = computed(function () {
    if (! $this->baby) {
        return collect();
    }

    $entries = $this->baby->feedEntries()->where('fed_at', '>=', now()->subDays(6)->startOfDay())->get();

    return collect(range(6, 0))->map(function ($daysAgo) use ($entries) {
        $day = now()->subDays($daysAgo);

        return [
            'label' => $day->format('D'),
            'oz' => (float) $entries->filter(fn ($entry) => $entry->fed_at->isSameDay($day))->sum('amount_oz'),
        ];
    })->values();
});

$resetQuickForms = function () {
    $this->quickFeedType = 'bottle';
    $this->quickFeedAt = now()->format('Y-m-d\TH:i');
    $this->quickFeedAmount = 3;
    $this->quickFeedSide = null;
    $this->quickDiaperIsWet = true;
    $this->quickDiaperIsDirty = false;
    $this->quickDiaperAt = now()->format('Y-m-d\TH:i');
    $this->quickDiaperConsistency = null;
};

$saveFeed = function () {
    if (! $this->baby) {
        return;
    }

    $this->validate([
        'quickFeedType' => ['required', 'in:breast,bottle,solid'],
        'quickFeedAt' => ['required', 'date'],
        'quickFeedAmount' => ['nullable', 'numeric', 'min:0', 'max:64'],
    ]);

    $this->baby->feedEntries()->create([
        'type' => $this->quickFeedType,
        'fed_at' => $this->quickFeedAt,
        'amount_oz' => $this->quickFeedType === 'bottle' ? $this->quickFeedAmount : null,
        'side' => $this->quickFeedSide,
    ]);

    $this->resetQuickForms();
};

$saveDiaper = function () {
    if (! $this->baby) {
        return;
    }

    $this->validate([
        'quickDiaperIsWet' => ['boolean'],
        'quickDiaperIsDirty' => ['boolean'],
        'quickDiaperAt' => ['required', 'date'],
    ]);

    if (! $this->quickDiaperIsWet && ! $this->quickDiaperIsDirty) {
        $this->addError('quickDiaperIsWet', 'Select at least one: pee or poop.');

        return;
    }

    $this->baby->diaperEntries()->create([
        'is_wet' => $this->quickDiaperIsWet,
        'is_dirty' => $this->quickDiaperIsDirty,
        'occurred_at' => $this->quickDiaperAt,
        'consistency' => $this->quickDiaperIsDirty ? $this->quickDiaperConsistency : null,
    ]);

    $this->resetQuickForms();
};

?>

@php
    // Volt computed properties are only reachable via $this->, so alias them
    // to plain variables once for readability in the rest of this template.
    $baby = $this->baby;
    $age = $this->age;
    $lastFeed = $this->lastFeed;
    $lastDiaper = $this->lastDiaper;
    $photos = $this->photos;
    $diaperTrend = $this->diaperTrend;
    $feedTrend = $this->feedTrend;
    $weightChart = $this->weightChart;
    $weightStatus = $this->weightStatus;
@endphp

<div x-data="{
        now: new Date(),
        localDateTimeInput() {
            const d = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        },
     }" x-init="setInterval(() => now = new Date(), 1000)">
    <x-slot name="header">
        <x-page-header title="Dashboard" />
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        @if ($this->babies->isEmpty())
            <x-card class="p-10 text-center">
                <p class="text-gray-700 font-medium">Welcome! Let's add your baby.</p>
                <p class="text-sm text-gray-500 mt-1">Create a profile to start tracking feeds, sleep, weight and milestones.</p>
                <a href="{{ route('babies.create') }}" wire:navigate class="inline-block mt-4">
                    <x-primary-button>+ Add your first baby</x-primary-button>
                </a>
            </x-card>
        @elseif ($baby)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <x-card class="p-6 flex flex-col justify-between gap-4 h-full">
                    <div class="flex items-center gap-3">
                        <x-avatar-upload :baby="$baby" size="w-14 h-14" icon-size="w-5 h-5" text-size="text-lg" />
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $baby->name }}</p>
                            <p class="text-sm text-gray-500">{{ $age->label() }} old</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xl sm:text-2xl font-bold text-gray-800 tabular-nums" x-text="now.toLocaleTimeString()"></p>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="now.toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric' })"></p>
                        <a href="{{ route('babies.show', $baby) }}" wire:navigate class="text-sm text-blue-600 hover:text-blue-700 mt-1 inline-block">View profile →</a>
                    </div>
                </x-card>

                <x-stat-card label="Last Feed" color="amber"
                    :value="$lastFeed ? $lastFeed->fed_at->diffForHumans() : '—'"
                    :meta="$lastFeed ? ucfirst($lastFeed->type).' · '.$lastFeed->fed_at->format('M j, g:i A') : 'Not logged yet'" />
                <x-stat-card label="Last Diaper" color="emerald"
                    :value="$lastDiaper ? $lastDiaper->occurred_at->diffForHumans() : '—'"
                    :meta="$lastDiaper ? $lastDiaper->label().' · '.$lastDiaper->occurred_at->format('M j, g:i A') : 'Not logged yet'" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @if ($photos && $photos->isNotEmpty())
                    <x-card class="p-3 sm:p-4 overflow-hidden">
                        <div x-data="{ slide: 0, total: {{ $photos->count() }} }"
                             x-init="total > 1 && setInterval(() => slide = (slide + 1) % total, 4000)"
                             class="relative h-56 sm:h-64">
                            <div class="relative rounded-xl overflow-hidden bg-gray-100 h-full">
                                @foreach ($photos as $i => $photo)
                                    <img src="{{ $photo->url() }}" alt="{{ $photo->caption ?? $baby->name }}"
                                         x-show="slide === {{ $i }}"
                                         :class="slide === {{ $i }} ? 'animate-kenburns' : ''"
                                         x-transition:enter="transition ease-out duration-700"
                                         x-transition:enter-start="opacity-0 scale-110"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute inset-0 w-full h-full object-cover">
                                @endforeach

                                @if ($photos->count() > 1)
                                    <button type="button" x-on:click="slide = (slide - 1 + total) % total"
                                            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white flex items-center justify-center shadow z-10">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" x-on:click="slide = (slide + 1) % total"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white flex items-center justify-center shadow z-10">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endif

                                @if ($photos->count() > 1)
                                    <div class="absolute bottom-3 inset-x-0 flex justify-center gap-2 z-10">
                                        @foreach ($photos as $i => $photo)
                                            <button type="button" x-on:click="slide = {{ $i }}"
                                                    :class="slide === {{ $i }} ? 'bg-amber-400 w-6' : 'bg-white/70 w-2'"
                                                    class="h-2 rounded-full transition-all"></button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </x-card>
                @else
                    <a href="{{ route('babies.photos.index', $baby) }}" wire:navigate>
                        <x-card class="p-7 text-center hover:shadow-md transition h-56 sm:h-64 flex items-center justify-center">
                            <p class="text-base text-gray-500">No photos yet — tap to add your first one.</p>
                        </x-card>
                    </a>
                @endif

                <div class="flex flex-col gap-3 h-full">
                    <button type="button" x-on:click="$wire.quickFeedAt = localDateTimeInput(); $dispatch('open-modal', 'quick-feed')"
                            class="flex-1 flex items-center justify-center w-full px-4 rounded-xl bg-amber-500 text-white font-semibold text-lg hover:bg-amber-600">
                        + Log Feed
                    </button>
                    <button type="button" x-on:click="$wire.quickDiaperAt = localDateTimeInput(); $dispatch('open-modal', 'quick-diaper')"
                            class="flex-1 flex items-center justify-center w-full px-4 rounded-xl bg-emerald-500 text-white font-semibold text-lg hover:bg-emerald-600">
                        + Log Diaper
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-card class="p-6">
                    <p class="font-semibold text-gray-800 mb-1">Diaper Activity</p>
                    <p class="text-xs text-gray-400 mb-3">Last 7 days</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Pee</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span> Poop</span>
                    </div>
                    <div wire:key="diaper-trend-{{ $diaperTrend->pluck('pee')->sum() }}-{{ $diaperTrend->pluck('poop')->sum() }}" style="height: 160px;">
                        <canvas
                            x-data
                            x-init="new window.Chart($el.getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: {{ Js::from($diaperTrend->pluck('label')) }},
                                    datasets: [
                                        { label: 'Pee', data: {{ Js::from($diaperTrend->pluck('pee')) }}, backgroundColor: '#3b82f6', borderRadius: 4, maxBarThickness: 16 },
                                        { label: 'Poop', data: {{ Js::from($diaperTrend->pluck('poop')) }}, backgroundColor: '#d97706', borderRadius: 4, maxBarThickness: 16 },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1f2937', padding: 8, cornerRadius: 8 } },
                                    scales: {
                                        x: { stacked: true, grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                                        y: { stacked: true, beginAtZero: true, ticks: { precision: 0, color: '#9ca3af', font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                                    },
                                },
                            })"
                        ></canvas>
                    </div>
                </x-card>

                <x-card class="p-6">
                    <p class="font-semibold text-gray-800 mb-1">Feeding Activity</p>
                    <p class="text-xs text-gray-400 mb-3">Bottle ounces · Last 7 days</p>
                    <div class="h-[1.375rem] mb-2"></div>
                    <div wire:key="feed-trend-{{ $feedTrend->pluck('oz')->sum() }}" style="height: 160px;">
                        <canvas
                            x-data
                            x-init="new window.Chart($el.getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: {{ Js::from($feedTrend->pluck('label')) }},
                                    datasets: [
                                        { label: 'oz', data: {{ Js::from($feedTrend->pluck('oz')) }}, backgroundColor: '#f59e0b', borderRadius: 4, maxBarThickness: 24 },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: '#1f2937', padding: 8, cornerRadius: 8,
                                            callbacks: { label: (ctx) => ctx.parsed.y + ' oz' },
                                        },
                                    },
                                    scales: {
                                        x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                                        y: { beginAtZero: true, ticks: { color: '#9ca3af', font: { size: 11 }, callback: (v) => v + ' oz' }, grid: { color: '#f3f4f6' } },
                                    },
                                },
                            })"
                        ></canvas>
                    </div>
                </x-card>
            </div>

            <x-card class="p-6">
                <div class="flex items-start justify-between gap-3 mb-1">
                    <div>
                        <a href="{{ route('babies.growth', $baby) }}" wire:navigate class="font-semibold text-lg text-gray-800 hover:text-blue-700">Growth Chart</a>
                        <p class="text-xs text-gray-400">Weight vs. typical median for age</p>
                    </div>
                    @if ($weightStatus)
                        <span @class([
                            'text-xs font-semibold px-2.5 py-1 rounded-full shrink-0',
                            'bg-emerald-50 text-emerald-700' => $weightStatus['color'] === 'emerald',
                            'bg-amber-50 text-amber-700' => $weightStatus['color'] === 'amber',
                        ])>{{ $weightStatus['label'] }}</span>
                    @endif
                </div>

                @if ($weightChart->count() < 2)
                    <x-empty-state title="Not enough data yet" subtitle="Log at least two weight entries to see the growth chart." />
                @else
                    <div class="flex items-center gap-4 text-xs text-gray-500 my-3">
                        <span class="flex items-center gap-1.5"><span class="w-4 h-0.5 rounded-full bg-blue-600 inline-block"></span> Baby's weight</span>
                        <span class="flex items-center gap-1.5"><span class="w-4 h-0.5 rounded-full bg-gray-300 inline-block"></span> Typical median</span>
                    </div>
                    <div wire:key="weight-chart-{{ $weightChart->pluck('actual')->sum() }}" style="height: 220px;">
                        <canvas
                            x-data
                            x-init="new window.Chart($el.getContext('2d'), {
                                type: 'line',
                                data: {
                                    labels: {{ Js::from($weightChart->pluck('label')) }},
                                    datasets: [
                                        { label: 'Weight (kg)', data: {{ Js::from($weightChart->pluck('actual')) }}, borderColor: '#1d4ed8', backgroundColor: 'rgba(29, 78, 216, 0.1)', borderWidth: 2, pointRadius: 4, pointBackgroundColor: '#1d4ed8', tension: 0.3, fill: true },
                                        { label: 'Typical median', data: {{ Js::from($weightChart->pluck('median')) }}, borderColor: '#9ca3af', borderWidth: 2, borderDash: [6, 4], pointRadius: 0, tension: 0.3, fill: false },
                                    ],
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1f2937', padding: 8, cornerRadius: 8 } },
                                    scales: {
                                        x: { grid: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                                        y: { ticks: { color: '#9ca3af', font: { size: 11 } }, grid: { color: '#f3f4f6' } },
                                    },
                                },
                            })"
                        ></canvas>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">General reference only, based on typical growth patterns — not a medical assessment. Talk to your pediatrician about any concerns.</p>
                @endif
            </x-card>

            <!-- Quick Add Feed Modal -->
            <x-modal name="quick-feed" max-width="sm">
                <form x-on:submit.prevent="$wire.saveFeed().then(() => $dispatch('close-modal', 'quick-feed'))" class="p-8 space-y-5">
                    <h3 class="text-xl font-semibold text-gray-800">Log a Feed</h3>

                    <div>
                        <x-input-label for="quickFeedType" value="Type" />
                        <select id="quickFeedType" wire:model="quickFeedType" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
                            <option value="breast">Breastfeeding</option>
                            <option value="bottle">Bottle</option>
                            <option value="solid">Solid food</option>
                        </select>
                    </div>

                    @if ($quickFeedType === 'breast')
                        <div>
                            <x-input-label for="quickFeedSide" value="Side" />
                            <select id="quickFeedSide" wire:model="quickFeedSide" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
                                <option value="">—</option>
                                <option value="left">Left</option>
                                <option value="right">Right</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                    @elseif ($quickFeedType === 'bottle')
                        <div>
                            <x-input-label for="quickFeedAmount" value="Amount (oz)" />
                            <x-text-input id="quickFeedAmount" wire:model="quickFeedAmount" type="number" step="0.5" min="0" class="mt-1 block w-full" />
                        </div>
                    @endif

                    <div>
                        <x-input-label for="quickFeedAt" value="Fed At" />
                        <x-text-input id="quickFeedAt" wire:model="quickFeedAt" type="datetime-local" class="mt-1 block w-full" />
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'quick-feed')">Cancel</x-secondary-button>
                        <x-primary-button>Save Feed</x-primary-button>
                    </div>
                </form>
            </x-modal>

            <!-- Quick Add Diaper Modal -->
            <x-modal name="quick-diaper" max-width="sm">
                <form x-on:submit.prevent="$wire.saveDiaper().then(() => $dispatch('close-modal', 'quick-diaper'))" class="p-8 space-y-5">
                    <h3 class="text-xl font-semibold text-gray-800">Log a Diaper Change</h3>

                    <div>
                        <x-input-label value="What happened?" />
                        <div class="mt-1 flex gap-3">
                            <label class="flex-1 flex items-center justify-center gap-2 rounded-lg border-2 px-4 py-4 cursor-pointer transition"
                                   :class="$wire.quickDiaperIsWet ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-500'">
                                <input type="checkbox" wire:model="quickDiaperIsWet" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                <span class="font-medium">Pee</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 rounded-lg border-2 px-4 py-4 cursor-pointer transition"
                                   :class="$wire.quickDiaperIsDirty ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-500'">
                                <input type="checkbox" wire:model="quickDiaperIsDirty" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                <span class="font-medium">Poop</span>
                            </label>
                        </div>
                        @error('quickDiaperIsWet') <x-input-error :messages="[$message]" class="mt-1" /> @enderror
                    </div>

                    @if ($quickDiaperIsDirty)
                        <div>
                            <x-input-label for="quickDiaperConsistency" value="Consistency" />
                            <select id="quickDiaperConsistency" wire:model="quickDiaperConsistency" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
                                <option value="">—</option>
                                <option value="soft">Soft</option>
                                <option value="firm">Firm</option>
                                <option value="runny">Runny</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    @endif

                    <div>
                        <x-input-label for="quickDiaperAt" value="Occurred At" />
                        <x-text-input id="quickDiaperAt" wire:model="quickDiaperAt" type="datetime-local" class="mt-1 block w-full" />
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'quick-diaper')">Cancel</x-secondary-button>
                        <x-primary-button>Save Diaper</x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endif
    </div>
</div>
