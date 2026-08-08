<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedEntryRequest extends FormRequest
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
            'type' => ['required', 'in:breast,bottle,solid'],
            'fed_at' => ['required', 'date'],
            'amount_oz' => ['nullable', 'numeric', 'min:0', 'max:64'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:300'],
            'side' => ['nullable', 'in:left,right,both'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
