<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit {{ $baby->name }}" :back="route('babies.show', $baby)" />
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-10">
            <form method="POST" action="{{ route('babies.update', $baby) }}" class="space-y-6">
                @csrf
                @method('PUT')
                @include('babies._form')

                <div class="flex justify-between items-center pt-2">
                    <form method="POST" action="{{ route('babies.destroy', $baby) }}"
                          onsubmit="return confirm('Delete {{ $baby->name }} and all their tracked data? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit">Delete Baby</x-danger-button>
                    </form>

                    <div class="flex gap-2">
                        <a href="{{ route('babies.show', $baby) }}" wire:navigate><x-secondary-button type="button">Cancel</x-secondary-button></a>
                        <x-primary-button>Save Changes</x-primary-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
