<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ $baby->name }}" :subtitle="$age->label().' old'" :back="route('babies.index')">
            <x-slot name="actions">
                <a href="{{ route('babies.edit', $baby) }}" wire:navigate>
                    <x-secondary-button>Edit</x-secondary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        @if (session('status'))
            <div class="rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="flex items-center gap-4">
            <x-avatar-upload :baby="$baby" size="w-20 h-20" icon-size="w-6 h-6" text-size="text-2xl" />
            <div>
                <p class="text-sm text-gray-500">Born {{ $baby->date_of_birth->format('F j, Y') }}</p>
                @if ($baby->birth_weight_kg)
                    <p class="text-sm text-gray-500">{{ $baby->birth_weight_kg }} kg at birth</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-stat-card label="Last Feed" color="amber"
                :value="$lastFeed ? $lastFeed->fed_at->diffForHumans() : '—'"
                :meta="$lastFeed ? ucfirst($lastFeed->type).' · '.$lastFeed->fed_at->format('M j, g:i A') : 'Not logged yet'" />
            <x-stat-card label="Last Diaper" color="emerald"
                :value="$lastDiaper ? $lastDiaper->occurred_at->diffForHumans() : '—'"
                :meta="$lastDiaper ? $lastDiaper->label().' · '.$lastDiaper->occurred_at->format('M j, g:i A') : 'Not logged yet'" />
            <x-stat-card label="Last Sleep" color="indigo"
                :value="$lastSleep ? $lastSleep->started_at->diffForHumans() : '—'"
                :meta="$lastSleep && $lastSleep->ended_at ? $lastSleep->durationMinutes().' min' : ($lastSleep ? 'In progress' : 'Not logged yet')" />
            <x-stat-card label="Last Weight" color="blue"
                :value="$lastWeight ? $lastWeight->weight_kg.' kg' : '—'"
                :meta="$lastWeight ? $lastWeight->measured_at->format('M j') : 'Not logged yet'" />
        </div>

        <x-card class="p-7">
            <p class="text-base font-semibold text-gray-700 mb-4">Quick log</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a href="{{ route('babies.feeds.create', $baby) }}" wire:navigate class="text-center px-4 py-4 rounded-xl bg-amber-50 text-amber-700 text-base font-semibold hover:bg-amber-100">Feed</a>
                <a href="{{ route('babies.diapers.create', $baby) }}" wire:navigate class="text-center px-4 py-4 rounded-xl bg-emerald-50 text-emerald-700 text-base font-semibold hover:bg-emerald-100">Diaper</a>
                <a href="{{ route('babies.sleeps.create', $baby) }}" wire:navigate class="text-center px-4 py-4 rounded-xl bg-indigo-50 text-indigo-700 text-base font-semibold hover:bg-indigo-100">Sleep</a>
                <a href="{{ route('babies.weights.create', $baby) }}" wire:navigate class="text-center px-4 py-4 rounded-xl bg-blue-50 text-blue-800 text-base font-semibold hover:bg-blue-100">Weight</a>
                <a href="{{ route('babies.milestones.create', $baby) }}" wire:navigate class="text-center px-4 py-4 rounded-xl bg-violet-50 text-violet-700 text-base font-semibold hover:bg-violet-100">Milestone</a>
                <a href="{{ route('babies.stories.create', $baby) }}" wire:navigate class="text-center px-4 py-4 rounded-xl bg-rose-50 text-rose-700 text-base font-semibold hover:bg-rose-100">Story</a>
            </div>
        </x-card>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <a href="{{ route('babies.growth', $baby) }}" wire:navigate>
                <x-card class="p-8 hover:shadow-md transition h-full">
                    <p class="font-semibold text-lg text-gray-800">Growth Chart</p>
                    <p class="text-base text-gray-500 mt-1">Weight over time</p>
                </x-card>
            </a>
            <a href="{{ route('babies.guide', $baby) }}" wire:navigate>
                <x-card class="p-8 hover:shadow-md transition h-full">
                    <p class="font-semibold text-lg text-gray-800">Parent's Guide</p>
                    <p class="text-base text-gray-500 mt-1">Tips &amp; goals for this age</p>
                </x-card>
            </a>
            <a href="{{ route('babies.pediatrician.edit', $baby) }}" wire:navigate>
                <x-card class="p-8 hover:shadow-md transition h-full">
                    <p class="font-semibold text-lg text-gray-800">Pediatrician</p>
                    <p class="text-base text-gray-500 mt-1">{{ $baby->pediatrician?->doctor_name ?? 'Add contact info' }}</p>
                </x-card>
            </a>
            <a href="{{ route('babies.photos.index', $baby) }}" wire:navigate>
                <x-card class="p-8 hover:shadow-md transition h-full">
                    <p class="font-semibold text-lg text-gray-800">Photos</p>
                    <p class="text-base text-gray-500 mt-1">{{ $baby->photos()->count() }} saved</p>
                </x-card>
            </a>
            <a href="{{ route('babies.stories.index', $baby) }}" wire:navigate>
                <x-card class="p-8 hover:shadow-md transition h-full">
                    <p class="font-semibold text-lg text-gray-800">Stories</p>
                    <p class="text-base text-gray-500 mt-1">{{ $baby->storyEntries()->count() }} shared</p>
                </x-card>
            </a>
        </div>

        <x-card class="p-7">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-gray-700">Recent milestones</p>
                <a href="{{ route('babies.milestones.index', $baby) }}" wire:navigate class="text-sm text-blue-600 hover:text-blue-700">View all</a>
            </div>

            @if ($recentMilestones->isEmpty())
                <p class="text-sm text-gray-500">No milestones recorded yet.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($recentMilestones as $milestone)
                        <li class="py-2.5 flex items-center justify-between gap-3">
                            <span class="text-sm text-gray-700">{{ $milestone->title }}</span>
                            <span class="text-xs text-gray-400 shrink-0">{{ $milestone->achieved_on->format('M j, Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>

        <div class="grid grid-cols-2 gap-3 text-center">
            <a href="{{ route('babies.feeds.index', $baby) }}" wire:navigate class="text-base text-gray-500 hover:text-blue-700 py-2.5">All Feeds</a>
            <a href="{{ route('babies.diapers.index', $baby) }}" wire:navigate class="text-base text-gray-500 hover:text-blue-700 py-2.5">All Diapers</a>
            <a href="{{ route('babies.sleeps.index', $baby) }}" wire:navigate class="text-base text-gray-500 hover:text-blue-700 py-2.5">All Sleep</a>
            <a href="{{ route('babies.weights.index', $baby) }}" wire:navigate class="text-base text-gray-500 hover:text-blue-700 py-2.5">All Weights</a>
            <a href="{{ route('babies.stories.index', $baby) }}" wire:navigate class="text-base text-gray-500 hover:text-blue-700 py-2.5">All Stories</a>
        </div>
    </div>
</x-app-layout>
