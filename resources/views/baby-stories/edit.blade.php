<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Story — {{ $baby->name }}" :back="route('babies.stories.index', $baby)" />
    </x-slot>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-10">
            <form method="POST" action="{{ route('babies.stories.update', [$baby, $story]) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                @include('baby-stories._fields')
                <div class="flex justify-between items-center">
                    <form method="POST" action="{{ route('babies.stories.destroy', [$baby, $story]) }}" onsubmit="return confirm('Delete this story?');">
                        @csrf @method('DELETE')
                        <x-danger-button type="submit">Delete</x-danger-button>
                    </form>
                    <div class="flex gap-2">
                        <a href="{{ route('babies.stories.index', $baby) }}" wire:navigate><x-secondary-button type="button">Cancel</x-secondary-button></a>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
