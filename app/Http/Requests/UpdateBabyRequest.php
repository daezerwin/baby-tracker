<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBabyRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:today'],
            'time_of_birth' => ['nullable', 'date_format:H:i'],
            'birth_weight_kg' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'birth_length_cm' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'blood_type' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
