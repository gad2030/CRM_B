<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->ignore($this->route('product'))
            ],
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0|max:999999999999.99',
            'cost_price' => 'nullable|numeric|min:0|max:999999999999.99',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }
}
