<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMilestoneEntryRequest extends FormRequest
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
            'milestone_definition_id' => ['nullable', 'exists:milestone_definitions,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'in:motor,cognitive,social,language'],
            'achieved_on' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
