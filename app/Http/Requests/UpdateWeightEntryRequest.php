<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeightEntryRequest extends FormRequest
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
            'weight_kg' => ['required', 'numeric', 'min:0', 'max:99'],
            'measured_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
