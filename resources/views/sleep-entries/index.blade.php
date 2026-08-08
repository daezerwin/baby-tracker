<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Sleep — {{ $baby->name }}" :back="route('babies.show', $baby)">
            <x-slot name="actions">
                <a href="{{ route('babies.sleeps.create', $baby) }}" wire:navigate><x-primary-button>+ Add</x-primary-button></a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <x-card class="p-7">
            @if ($entries->isEmpty())
                <x-empty-state title="No sleep logged yet" />
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($entries as $entry)
                        <x-timeline-item
                            :title="$entry->started_at->format('M j, g:i A').' — '.($entry->ended_at ? $entry->ended_at->format('g:i A') : 'ongoing')"
                            :time="$entry->ended_at ? $entry->durationMinutes().' min' : 'In progress'"
                            :edit-url="route('babies.sleeps.edit', [$baby, $entry])"
                            :delete-url="route('babies.sleeps.destroy', [$baby, $entry])">
                            @if ($entry->notes)
                                <x-slot name="notes">{{ $entry->notes }}</x-slot>
                            @endif
                        </x-timeline-item>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>
</x-app-layout>
