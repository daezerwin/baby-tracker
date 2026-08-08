@php($entry = $entry ?? null)

<div x-data="{
        type: '{{ old('type', $entry?->type ?? 'breast') }}',
        amountOz: {{ old('amount_oz', $entry?->amount_oz) !== null ? old('amount_oz', $entry?->amount_oz) : 'null' }}
     }"
     x-effect="if (type === 'bottle' && (amountOz === null || amountOz === '')) amountOz = 3"
     class="space-y-5">
    <div>
        <x-input-label for="type" value="Type" />
        <select id="type" name="type" required x-model="type"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
            @foreach (['breast' => 'Breastfeeding', 'bottle' => 'Bottle', 'solid' => 'Solid food'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-1" />
    </div>

    <div x-show="type === 'breast'">
        <x-input-label for="side" value="Side (optional)" />
        <select id="side" name="side" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
            <option value="">—</option>
            @foreach (['left' => 'Left', 'right' => 'Right', 'both' => 'Both'] as $value => $label)
                <option value="{{ $value }}" @selected(old('side', $entry?->side) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('side')" class="mt-1" />
    </div>

    <div x-show="type === 'bottle'">
        <x-input-label value="Amount (oz)" />
        <div class="mt-1 grid grid-cols-5 gap-2">
            @foreach ([0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5] as $amount)
                <label class="flex items-center justify-center rounded-lg border-2 py-3 text-sm font-medium cursor-pointer transition"
                       :class="amountOz == {{ $amount }} ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600'">
                    <input type="radio" name="amount_oz" x-model.number="amountOz" value="{{ $amount }}" :disabled="type !== 'bottle'" class="sr-only">
                    {{ rtrim(rtrim(number_format($amount, 1), '0'), '.') }}
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('amount_oz')" class="mt-1" />
    </div>
</div>

<div>
    <x-input-label for="duration_minutes" value="Duration (min, optional)" />
    <x-text-input id="duration_minutes" name="duration_minutes" type="number" class="mt-1 block w-full" value="{{ old('duration_minutes', $entry?->duration_minutes) }}" />
    <x-input-error :messages="$errors->get('duration_minutes')" class="mt-1" />
</div>

<div>
    <x-input-label for="fed_at" value="Fed At" />
    <x-text-input id="fed_at" name="fed_at" type="datetime-local" class="mt-1 block w-full" required
        value="{{ old('fed_at', $entry?->fed_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" />
    <x-input-error :messages="$errors->get('fed_at')" class="mt-1" />
</div>

<div>
    <x-input-label for="notes" value="Notes (optional)" />
    <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">{{ old('notes', $entry?->notes) }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
</div>
