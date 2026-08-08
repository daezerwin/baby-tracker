@php($baby = $baby ?? null)

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus
            value="{{ old('name', $baby?->name) }}" />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="sex" value="Sex" />
        <select id="sex" name="sex" required
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
            <option value="">Select...</option>
            @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                <option value="{{ $value }}" @selected(old('sex', $baby?->sex) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('sex')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="blood_type" value="Blood Type (optional)" />
        <x-text-input id="blood_type" name="blood_type" type="text" class="mt-1 block w-full" placeholder="e.g. O+"
            value="{{ old('blood_type', $baby?->blood_type) }}" />
        <x-input-error :messages="$errors->get('blood_type')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="date_of_birth" value="Date of Birth" />
        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" required
            value="{{ old('date_of_birth', $baby?->date_of_birth?->format('Y-m-d')) }}" />
        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="time_of_birth" value="Time of Birth (optional)" />
        <x-text-input id="time_of_birth" name="time_of_birth" type="time" class="mt-1 block w-full"
            value="{{ old('time_of_birth', $baby?->time_of_birth) }}" />
        <x-input-error :messages="$errors->get('time_of_birth')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="birth_weight_kg" value="Birth Weight (kg, optional)" />
        <x-text-input id="birth_weight_kg" name="birth_weight_kg" type="number" step="0.01" class="mt-1 block w-full"
            value="{{ old('birth_weight_kg', $baby?->birth_weight_kg) }}" />
        <x-input-error :messages="$errors->get('birth_weight_kg')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="birth_length_cm" value="Birth Length (cm, optional)" />
        <x-text-input id="birth_length_cm" name="birth_length_cm" type="number" step="0.01" class="mt-1 block w-full"
            value="{{ old('birth_length_cm', $baby?->birth_length_cm) }}" />
        <x-input-error :messages="$errors->get('birth_length_cm')" class="mt-1" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="notes" value="Notes (optional)" />
        <textarea id="notes" name="notes" rows="3"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">{{ old('notes', $baby?->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
    </div>
</div>
