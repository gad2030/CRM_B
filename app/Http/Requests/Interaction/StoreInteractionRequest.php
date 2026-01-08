<?php

namespace App\Http\Requests\Interaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInteractionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|max:100',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'account_id' => [
                'nullable',
                Rule::requiredIf(function () {
                    return !$this->contact_id && !$this->lead_id && !$this->opportunity_id;
                }),
                'exists:accounts,id',
            ],
            'contact_id' => 'nullable|exists:contacts,id',
            'lead_id' => 'nullable|exists:leads,id',
            'opportunity_id' => 'nullable|exists:opportunities,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'account_id.required' => 'At least one related entity (account, contact, lead, or opportunity) is required.',
        ];
    }
}

