<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Feeds — {{ $baby->name }}" :back="route('babies.show', $baby)">
            <x-slot name="actions">
                <a href="{{ route('babies.import.show', $baby) }}" wire:navigate><x-secondary-button type="button">Import CSV</x-secondary-button></a>
                <a href="{{ route('babies.feeds.create', $baby) }}" wire:navigate><x-primary-button>+ Add</x-primary-button></a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <x-card class="p-7">
            @if ($entries->isEmpty())
                <x-empty-state title="No feeds logged yet" subtitle="Log a feed to start building the timeline." />
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($entries as $entry)
                        <x-timeline-item
                            :title="ucfirst($entry->type).($entry->side ? ' · '.ucfirst($entry->side) : '')"
                            :time="$entry->fed_at->format('M j, Y g:i A')"
                            :meta="collect([$entry->amount_oz ? $entry->amount_oz.' oz' : null, $entry->duration_minutes ? $entry->duration_minutes.' min' : null])->filter()->join(' · ')"
                            :edit-url="route('babies.feeds.edit', [$baby, $entry])"
                            :delete-url="route('babies.feeds.destroy', [$baby, $entry])">
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
