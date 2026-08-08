@php($entry = $entry ?? null)

<div>
    <x-input-label for="started_at" value="Started At" />
    <x-text-input id="started_at" name="started_at" type="datetime-local" class="mt-1 block w-full" required
        value="{{ old('started_at', $entry?->started_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" />
    <x-input-error :messages="$errors->get('started_at')" class="mt-1" />
</div>

<div>
    <x-input-label for="ended_at" value="Ended At (leave blank if still sleeping)" />
    <x-text-input id="ended_at" name="ended_at" type="datetime-local" class="mt-1 block w-full"
        value="{{ old('ended_at', $entry?->ended_at?->format('Y-m-d\TH:i')) }}" />
    <x-input-error :messages="$errors->get('ended_at')" class="mt-1" />
</div>

<div>
    <x-input-label for="notes" value="Notes (optional)" />
    <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">{{ old('notes', $entry?->notes) }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
</div>
