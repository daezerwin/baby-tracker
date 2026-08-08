<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PediatricianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('baby'));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'clinic_name' => ['nullable', 'string', 'max:255'],
            'doctor_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'next_appointment_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
