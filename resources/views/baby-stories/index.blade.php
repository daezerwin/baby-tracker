<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Stories — {{ $baby->name }}" :back="route('babies.show', $baby)">
            <x-slot name="actions">
                <a href="{{ route('babies.stories.create', $baby) }}" wire:navigate><x-primary-button>+ Add Story</x-primary-button></a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        @if ($stories->isEmpty())
            <x-card class="p-10 text-center">
                <x-empty-state title="No stories yet" subtitle="Capture a moment — a photo, a caption, or both." />
                <a href="{{ route('babies.stories.create', $baby) }}" wire:navigate class="inline-block mt-4">
                    <x-primary-button>+ Add your first story</x-primary-button>
                </a>
            </x-card>
        @else
            @php $grouped = $stories->getCollection()->groupBy(fn ($story) => $story->occurred_at->format('Y-m-d')); @endphp
            <div class="space-y-6">
                @foreach ($grouped as $date => $dayStories)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2 px-1">
                            {{ \Illuminate\Support\Carbon::parse($date)->isToday() ? 'Today' : (\Illuminate\Support\Carbon::parse($date)->isYesterday() ? 'Yesterday' : \Illuminate\Support\Carbon::parse($date)->format('l, F j, Y')) }}
                        </p>
                        <div class="space-y-4">
                            @foreach ($dayStories as $story)
                                <x-card class="overflow-hidden">
                                    @if ($story->media_path)
                                        @if ($story->isVideo())
                                            <video src="{{ $story->mediaUrl() }}" class="w-full max-h-96 bg-black" controls playsinline></video>
                                        @else
                                            <img src="{{ $story->mediaUrl() }}" alt="" class="w-full max-h-96 object-cover">
                                        @endif
                                    @endif
                                    <div class="p-5">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="text-sm text-gray-400">{{ $story->occurred_at->format('g:i A') }}</p>
                                            <div class="flex items-center gap-3 shrink-0">
                                                <a href="{{ route('babies.stories.edit', [$baby, $story]) }}" wire:navigate class="text-sm text-blue-600 hover:text-blue-700">Edit</a>
                                                <form method="POST" action="{{ route('babies.stories.destroy', [$baby, $story]) }}" onsubmit="return confirm('Delete this story?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-sm text-gray-400 hover:text-red-600">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        @if ($story->caption)
                                            <p class="text-base text-gray-700 mt-2 whitespace-pre-line">{{ $story->caption }}</p>
                                        @endif
                                    </div>
                                </x-card>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $stories->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
