@php($entry = $entry ?? null)

<div x-data="{
        isWet: {{ old('is_wet', $entry?->is_wet ?? true) ? 'true' : 'false' }},
        isDirty: {{ old('is_dirty', $entry?->is_dirty ?? false) ? 'true' : 'false' }}
     }" class="space-y-5">
    <div>
        <x-input-label value="What happened?" />
        <div class="mt-1 flex gap-3">
            <label class="flex-1 flex items-center justify-center gap-2 rounded-lg border-2 px-4 py-4 cursor-pointer transition"
                   :class="isWet ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-500'">
                <input type="checkbox" name="is_wet" value="1" x-model="isWet" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                <span class="font-medium">Pee</span>
            </label>
            <label class="flex-1 flex items-center justify-center gap-2 rounded-lg border-2 px-4 py-4 cursor-pointer transition"
                   :class="isDirty ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-500'">
                <input type="checkbox" name="is_dirty" value="1" x-model="isDirty" class="rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                <span class="font-medium">Poop</span>
            </label>
        </div>
        <x-input-error :messages="$errors->get('is_wet')" class="mt-1" />
    </div>

    <div x-show="isDirty">
        <x-input-label for="consistency" value="Consistency (optional)" />
        <select id="consistency" name="consistency" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
            <option value="">—</option>
            @foreach (['soft' => 'Soft', 'firm' => 'Firm', 'runny' => 'Runny', 'hard' => 'Hard'] as $value => $label)
                <option value="{{ $value }}" @selected(old('consistency', $entry?->consistency) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('consistency')" class="mt-1" />
    </div>
</div>

<div>
    <x-input-label for="occurred_at" value="Occurred At" />
    <x-text-input id="occurred_at" name="occurred_at" type="datetime-local" class="mt-1 block w-full" required
        value="{{ old('occurred_at', $entry?->occurred_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" />
    <x-input-error :messages="$errors->get('occurred_at')" class="mt-1" />
</div>

<div>
    <x-input-label for="notes" value="Notes (optional)" />
    <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">{{ old('notes', $entry?->notes) }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
</div>
