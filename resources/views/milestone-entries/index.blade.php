<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Milestones — {{ $baby->name }}" :back="route('babies.show', $baby)">
            <x-slot name="actions">
                <a href="{{ route('babies.milestones.create', $baby) }}" wire:navigate><x-primary-button>+ Add</x-primary-button></a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        @if (session('status'))
            <div class="rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <x-card class="p-7">
            <p class="text-sm font-semibold text-gray-700 mb-3">Achieved</p>
            @if ($entries->isEmpty())
                <x-empty-state title="No milestones recorded yet" />
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($entries as $entry)
                        <x-timeline-item
                            :title="$entry->title"
                            :time="$entry->achieved_on->format('M j, Y')"
                            :meta="$entry->category ? ucfirst($entry->category) : null"
                            :edit-url="route('babies.milestones.edit', [$baby, $entry])"
                            :delete-url="route('babies.milestones.destroy', [$baby, $entry])">
                            @if ($entry->notes)
                                <x-slot name="notes">{{ $entry->notes }}</x-slot>
                            @endif
                        </x-timeline-item>
                    @endforeach
                </ul>
            @endif
        </x-card>

        @if ($upcoming->isNotEmpty())
            <x-card class="p-7">
                <p class="text-sm font-semibold text-gray-700 mb-3">Milestones to look forward to</p>
                <ul class="divide-y divide-gray-100">
                    @foreach ($upcoming as $definition)
                        <li class="py-2.5">
                            <p class="text-sm font-medium text-gray-800">{{ $definition->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $definition->description }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Typically {{ $definition->age_min_weeks }}–{{ $definition->age_max_weeks }} weeks · {{ ucfirst($definition->category) }}</p>
                        </li>
                    @endforeach
                </ul>
            </x-card>
        @endif
    </div>
</x-app-layout>
