<x-app-layout>
    <x-slot name="header">
        <x-page-header title="My Babies" subtitle="Everyone you're tracking">
            <x-slot name="actions">
                <a href="{{ route('babies.create') }}" wire:navigate>
                    <x-primary-button>+ Add Baby</x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        @if ($babies->isEmpty())
            <x-card>
                <x-empty-state title="No babies yet" subtitle="Add your baby's details to start tracking feeds, sleep, weight and milestones.">
                    <x-slot name="action">
                        <a href="{{ route('babies.create') }}" wire:navigate>
                            <x-primary-button>+ Add your first baby</x-primary-button>
                        </a>
                    </x-slot>
                </x-empty-state>
            </x-card>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach ($babies as $baby)
                    <a href="{{ route('babies.show', $baby) }}" wire:navigate>
                        <x-card class="p-7 hover:shadow-md transition h-full">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-lg shrink-0">
                                    {{ Str::substr($baby->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $baby->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $baby->age()->label() }} old</p>
                                </div>
                            </div>
                        </x-card>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
