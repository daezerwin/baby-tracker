<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Parent's Guide — {{ $baby->name }}" :subtitle="$baby->age()->label().' old'" :back="route('babies.show', $baby)" />
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        @if (session('status'))
            <div class="rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="rounded-xl bg-amber-50 text-amber-800 text-xs px-4 py-3">
            General information only, based on typical development — always follow your pediatrician's guidance for your baby specifically.
        </div>

        @if (! $guide)
            <x-card class="p-10 text-center">
                <p class="text-gray-700 font-medium">No guide content for this age yet.</p>
                <p class="text-sm text-gray-500 mt-1">Check back — we cover 0 to 24 months.</p>
            </x-card>
        @else
            <x-card class="p-10">
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide">{{ $guide->age_label }}</p>
                <h3 class="text-lg font-semibold text-gray-800 mt-1">This {{ str_contains($guide->age_label, 'Week') ? 'week' : 'month' }}'s goals</h3>
                <p class="text-sm text-gray-600 mt-2">{{ $guide->weekly_goals }}</p>
            </x-card>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-card class="p-7">
                    <p class="text-sm font-semibold text-amber-600">Feeding</p>
                    <p class="text-sm text-gray-600 mt-1.5">{{ $guide->feeding_tips }}</p>
                </x-card>
                <x-card class="p-7">
                    <p class="text-sm font-semibold text-indigo-600">Sleep</p>
                    <p class="text-sm text-gray-600 mt-1.5">{{ $guide->sleep_tips }}</p>
                </x-card>
                <x-card class="p-7">
                    <p class="text-sm font-semibold text-violet-600">Development</p>
                    <p class="text-sm text-gray-600 mt-1.5">{{ $guide->development_tips }}</p>
                </x-card>
                <x-card class="p-7">
                    <p class="text-sm font-semibold text-blue-700">Safety</p>
                    <p class="text-sm text-gray-600 mt-1.5">{{ $guide->safety_tips }}</p>
                </x-card>
            </div>
        @endif

        <x-card class="p-7">
            <p class="text-sm font-semibold text-gray-700 mb-3">Milestones around this age</p>
            @if ($milestones->isEmpty())
                <p class="text-sm text-gray-500">No milestones catalogued for this age range.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($milestones as $definition)
                        @php($achieved = in_array($definition->id, $achievedMilestoneIds))
                        <li class="py-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $definition->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $definition->description }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($definition->category) }} &middot; typically {{ $definition->age_min_weeks }}–{{ $definition->age_max_weeks }} weeks</p>
                            </div>
                            @if ($achieved)
                                <span class="text-xs text-emerald-600 font-medium shrink-0">✓ Achieved</span>
                            @else
                                <form method="POST" action="{{ route('babies.milestones.store', $baby) }}" class="shrink-0">
                                    @csrf
                                    <input type="hidden" name="milestone_definition_id" value="{{ $definition->id }}">
                                    <input type="hidden" name="title" value="{{ $definition->title }}">
                                    <input type="hidden" name="category" value="{{ $definition->category }}">
                                    <input type="hidden" name="achieved_on" value="{{ now()->format('Y-m-d') }}">
                                    <input type="hidden" name="return" value="guide">
                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Mark achieved</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>
</x-app-layout>
