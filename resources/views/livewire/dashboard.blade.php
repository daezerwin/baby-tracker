<?php

use Illuminate\Support\Facades\Auth;
use function Livewire\Volt\{state, computed, mount, layout};

layout('layouts.app');

state([
    'babyId' => null,
    'quickFeedType' => 'bottle',
    'quickFeedAt' => null,
    'quickFeedAmount' => null,
    'quickFeedSide' => null,
    'quickFeedNotes' => null,
    'quickDiaperIsWet' => true,
    'quickDiaperIsDirty' => false,
    'quickDiaperAt' => null,
    'quickDiaperConsistency' => null,
    'quickDiaperNotes' => null,
    'chartWeekOffset' => 0,
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

$chartWindowEnd = computed(fn () => now()->subDays(7 * $this->chartWeekOffset)->endOfDay());

$chartWindowStart = computed(fn () => $this->chartWindowEnd->copy()->subDays(6)->startOfDay());

$chartRangeLabel = computed(function () {
    $start = $this->chartWindowStart;
    $end = $this->chartWindowEnd;

    return $start->format('Y') === $end->format('Y')
        ? $start->format('M j').' – '.$end->format('M j')
        : $start->format('M j, Y').' – '.$end->format('M j, Y');
});

$diaperTrend = computed(function () {
    if (! $this->baby) {
        return collect();
    }

    $start = $this->chartWindowStart;
    $end = $this->chartWindowEnd;

    $entries = $this->baby->diaperEntries()->whereBetween('occurred_at', [$start, $end])->get();

    return collect(range(6, 0))->map(function ($daysAgo) use ($entries, $end) {
        $day = $end->copy()->subDays($daysAgo);
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

    $start = $this->chartWindowStart;
    $end = $this->chartWindowEnd;

    $entries = $this->baby->feedEntries()->whereBetween('fed_at', [$start, $end])->get();

    return collect(range(6, 0))->map(function ($daysAgo) use ($entries, $end) {
        $day = $end->copy()->subDays($daysAgo);

        return [
            'label' => $day->format('D'),
            'oz' => (float) $entries->filter(fn ($entry) => $entry->fed_at->isSameDay($day))->sum('amount_oz'),
        ];
    })->values();
});

$prevChartWeek = function () {
    $this->chartWeekOffset++;
};

$nextChartWeek = function () {
    if ($this->chartWeekOffset > 0) {
        $this->chartWeekOffset--;
    }
};

$resetQuickForms = function () {
    $this->quickFeedType = 'bottle';
    $this->quickFeedAt = now()->format('Y-m-d\TH:i');
    $this->quickFeedAmount = 3;
    $this->quickFeedSide = null;
    $this->quickFeedNotes = null;
    $this->quickDiaperIsWet = true;
    $this->quickDiaperIsDirty = false;
    $this->quickDiaperAt = now()->format('Y-m-d\TH:i');
    $this->quickDiaperConsistency = null;
    $this->quickDiaperNotes = null;
};

$saveFeed = function () {
    if (! $this->baby) {
        return;
    }

    $this->validate([
        'quickFeedType' => ['required', 'in:breast,bottle,solid'],
        'quickFeedAt' => ['required', 'date'],
        'quickFeedAmount' => ['nullable', 'numeric', 'min:0', 'max:64'],
        'quickFeedNotes' => ['nullable', 'string'],
    ]);

    $this->baby->feedEntries()->create([
        'type' => $this->quickFeedType,
        'fed_at' => $this->quickFeedAt,
        'amount_oz' => $this->quickFeedType === 'bottle' ? $this->quickFeedAmount : null,
        'side' => $this->quickFeedSide,
        'notes' => $this->quickFeedNotes,
    ]);

    $this->resetQuickForms();

    // Close the modal from the server, only on this confirmed-success path —
    // closing it from a client-side .then() on the Livewire call fired even
    // when saving failed (e.g. the addError() case below), since that
    // request still resolves normally.
    $this->dispatch('close-modal', 'quick-feed');
};

$saveDiaper = function () {
    if (! $this->baby) {
        return;
    }

    $this->validate([
        'quickDiaperIsWet' => ['boolean'],
        'quickDiaperIsDirty' => ['boolean'],
        'quickDiaperAt' => ['required', 'date'],
        'quickDiaperNotes' => ['nullable', 'string'],
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
        'notes' => $this->quickDiaperNotes,
    ]);

    $this->resetQuickForms();
    $this->dispatch('close-modal', 'quick-diaper');
};

?>

@php
    // Volt computed properties are only reachable via $this->, so alias them
    // to plain variables once for readability in the rest of this template.
    $baby = $this->baby;
    $age = $this->age;
    $lastFeed = $this->lastFeed;
    $lastDiaper = $this->lastDiaper;
    $diaperTrend = $this->diaperTrend;
    $feedTrend = $this->feedTrend;
    $chartRangeLabel = $this->chartRangeLabel;
@endphp

<div x-data>
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
                        <p class="text-xl sm:text-2xl font-bold text-gray-800 tabular-nums" x-text="$store.clock.now.toLocaleTimeString()"></p>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="$store.clock.now.toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric' })"></p>
                        <a href="{{ route('babies.show', $baby) }}" wire:navigate class="text-sm text-blue-600 hover:text-blue-700 mt-1 inline-block">View profile →</a>
                    </div>
                </x-card>

                <x-stat-card label="Last Feed" color="amber"
                    :value="$lastFeed ? $lastFeed->fed_at->diffForHumans() : '—'"
                    :live-since="$lastFeed?->fed_at?->toJSON()"
                    :subvalue="$lastFeed?->formattedAmount()"
                    :meta="$lastFeed ? ucfirst($lastFeed->type).' · '.$lastFeed->fed_at->format('M j, g:i A') : 'Not logged yet'"
                    :link="route('babies.feeds.index', $baby)" link-label="View all feeds" />
                <x-stat-card label="Last Diaper" color="emerald"
                    :value="$lastDiaper ? $lastDiaper->occurred_at->diffForHumans() : '—'"
                    :live-since="$lastDiaper?->occurred_at?->toJSON()"
                    :meta="$lastDiaper ? $lastDiaper->label().' · '.$lastDiaper->occurred_at->format('M j, g:i A') : 'Not logged yet'"
                    :link="route('babies.diapers.index', $baby)" link-label="View all diapers" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <button type="button" x-on:click="$wire.quickFeedAt = $store.clock.localDateTimeInput(); $dispatch('open-modal', 'quick-feed')"
                        class="flex items-center justify-center w-full px-6 py-6 rounded-2xl bg-amber-500 text-white font-semibold text-lg shadow-sm hover:bg-amber-600 hover:shadow-md transition">
                    + Log Feed
                </button>
                <button type="button" x-on:click="$wire.quickDiaperAt = $store.clock.localDateTimeInput(); $dispatch('open-modal', 'quick-diaper')"
                        class="flex items-center justify-center w-full px-6 py-6 rounded-2xl bg-emerald-500 text-white font-semibold text-lg shadow-sm hover:bg-emerald-600 hover:shadow-md transition">
                    + Log Diaper
                </button>
            </div>

            <div class="flex items-center justify-between gap-3">
                <button type="button" wire:click="prevChartWeek"
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50">
                    ‹
                </button>
                <p class="text-sm font-medium text-gray-600">{{ $chartRangeLabel }}</p>
                <button type="button" wire:click="nextChartWeek" @if ($this->chartWeekOffset === 0) disabled @endif
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                    ›
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-card class="p-6">
                    <p class="font-semibold text-gray-800 mb-1">Diaper Activity</p>
                    <p class="text-xs text-gray-400 mb-3">{{ $chartRangeLabel }}</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Pee</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span> Poop</span>
                    </div>
                    @php $diaperMax = max(1, $diaperTrend->map(fn ($day) => $day['pee'] + $day['poop'])->max()); @endphp
                    <div class="space-y-2.5">
                        @foreach ($diaperTrend as $day)
                            <div class="flex items-center gap-3">
                                <span class="w-8 text-xs font-medium text-gray-500 shrink-0">{{ $day['label'] }}</span>
                                <div class="flex-1 h-3 rounded-full bg-gray-100 overflow-hidden flex">
                                    <div class="h-full bg-blue-500" style="width: {{ $day['pee'] / $diaperMax * 100 }}%"></div>
                                    <div class="h-full bg-amber-600" style="width: {{ $day['poop'] / $diaperMax * 100 }}%"></div>
                                </div>
                                <span class="w-16 text-xs text-gray-600 text-right shrink-0 tabular-nums">{{ $day['pee'] }} + {{ $day['poop'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <x-card class="p-6">
                    <p class="font-semibold text-gray-800 mb-1">Feeding Activity</p>
                    <p class="text-xs text-gray-400 mb-3">Bottle ounces · {{ $chartRangeLabel }}</p>
                    @php $feedMax = max(1, $feedTrend->pluck('oz')->max()); @endphp
                    <div class="space-y-2.5">
                        @foreach ($feedTrend as $day)
                            <div class="flex items-center gap-3">
                                <span class="w-8 text-xs font-medium text-gray-500 shrink-0">{{ $day['label'] }}</span>
                                <div class="flex-1 h-3 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-amber-500" style="width: {{ $day['oz'] / $feedMax * 100 }}%"></div>
                                </div>
                                <span class="w-16 text-xs text-gray-600 text-right shrink-0 tabular-nums">{{ rtrim(rtrim(number_format($day['oz'], 1), '0'), '.') }} oz</span>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            </div>

            <!-- Quick Add Feed Modal -->
            <x-modal name="quick-feed" max-width="sm">
                <form x-on:submit.prevent="$wire.saveFeed()" class="p-8 space-y-5">
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
                            <x-input-label value="Amount (oz)" />
                            <div class="mt-1 grid grid-cols-5 gap-2">
                                @foreach ([0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5] as $amount)
                                    <label class="flex items-center justify-center rounded-lg border-2 py-3 text-sm font-medium cursor-pointer transition"
                                           :class="$wire.quickFeedAmount == {{ $amount }} ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600'">
                                        <input type="radio" wire:model="quickFeedAmount" value="{{ $amount }}" class="sr-only">
                                        {{ rtrim(rtrim(number_format($amount, 1), '0'), '.') }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <x-input-label for="quickFeedAt" value="Fed At" />
                        <x-text-input id="quickFeedAt" wire:model="quickFeedAt" type="datetime-local" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="quickFeedNotes" value="Notes (optional)" />
                        <textarea id="quickFeedNotes" wire:model="quickFeedNotes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'quick-feed')">Cancel</x-secondary-button>
                        <x-primary-button>Save Feed</x-primary-button>
                    </div>
                </form>
            </x-modal>

            <!-- Quick Add Diaper Modal -->
            <x-modal name="quick-diaper" max-width="sm">
                <form x-on:submit.prevent="$wire.saveDiaper()" class="p-8 space-y-5">
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

                    <div>
                        <x-input-label for="quickDiaperNotes" value="Notes (optional)" />
                        <textarea id="quickDiaperNotes" wire:model="quickDiaperNotes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600"></textarea>
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
