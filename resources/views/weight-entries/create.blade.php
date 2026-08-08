<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Weight — {{ $baby->name }}" :back="route('babies.weights.index', $baby)" />
    </x-slot>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-card class="p-10">
            <form method="POST" action="{{ route('babies.weights.store', $baby) }}" class="space-y-5">
                @csrf
                <div>
                    <x-input-label for="weight_kg" value="Weight (kg)" />
                    <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.01" class="mt-1 block w-full" required autofocus value="{{ old('weight_kg') }}" />
                    <x-input-error :messages="$errors->get('weight_kg')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="measured_at" value="Measured At" />
                    <x-text-input id="measured_at" name="measured_at" type="datetime-local" class="mt-1 block w-full" required value="{{ old('measured_at', now()->format('Y-m-d\TH:i')) }}" />
                    <x-input-error :messages="$errors->get('measured_at')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="notes" value="Notes (optional)" />
                    <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('babies.weights.index', $baby) }}" wire:navigate><x-secondary-button type="button">Cancel</x-secondary-button></a>
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
