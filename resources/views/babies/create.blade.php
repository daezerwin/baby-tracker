<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add a Baby" :back="route('babies.index')" />
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-10">
            <form method="POST" action="{{ route('babies.store') }}" class="space-y-6">
                @csrf
                @include('babies._form')

                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('babies.index') }}" wire:navigate><x-secondary-button type="button">Cancel</x-secondary-button></a>
                    <x-primary-button>Save Baby</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
