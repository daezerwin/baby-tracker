<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Pediatrician — {{ $baby->name }}" :back="route('babies.show', $baby)" />
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <x-card class="p-10">
            <form method="POST" action="{{ route('babies.pediatrician.update', $baby) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="doctor_name" value="Doctor's Name" />
                        <x-text-input id="doctor_name" name="doctor_name" type="text" class="mt-1 block w-full" value="{{ old('doctor_name', $pediatrician?->doctor_name) }}" />
                        <x-input-error :messages="$errors->get('doctor_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="clinic_name" value="Clinic / Practice" />
                        <x-text-input id="clinic_name" name="clinic_name" type="text" class="mt-1 block w-full" value="{{ old('clinic_name', $pediatrician?->clinic_name) }}" />
                        <x-input-error :messages="$errors->get('clinic_name')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="phone" value="Phone" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone', $pediatrician?->phone) }}" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $pediatrician?->email) }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="address" value="Address" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" value="{{ old('address', $pediatrician?->address) }}" />
                        <x-input-error :messages="$errors->get('address')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="next_appointment_at" value="Next Appointment" />
                        <x-text-input id="next_appointment_at" name="next_appointment_at" type="datetime-local" class="mt-1 block w-full"
                            value="{{ old('next_appointment_at', $pediatrician?->next_appointment_at?->format('Y-m-d\TH:i')) }}" />
                        <x-input-error :messages="$errors->get('next_appointment_at')" class="mt-1" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">{{ old('notes', $pediatrician?->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('babies.show', $baby) }}" wire:navigate><x-secondary-button type="button">Cancel</x-secondary-button></a>
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
