<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiaperEntryRequest extends FormRequest
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
            'is_wet' => ['sometimes', 'boolean'],
            'is_dirty' => ['sometimes', 'boolean'],
            'consistency' => ['nullable', 'in:soft,firm,runny,hard'],
            'occurred_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->boolean('is_wet') && ! $this->boolean('is_dirty')) {
                $validator->errors()->add('is_wet', 'Select at least one: pee or poop.');
            }
        });
    }
}
