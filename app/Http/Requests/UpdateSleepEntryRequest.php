<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSleepEntryRequest extends FormRequest
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
            'started_at' => ['required', 'date'],
            'ended_at' => ['nullable', 'date', 'after:started_at'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
