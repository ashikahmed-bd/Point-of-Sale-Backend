<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleReturnRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $saleReturn = $this->route('saleReturn');

        return [
            'sale_id' => ['required', 'exists:sales,id'],
            'return_no' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('sale_returns', 'return_no')->ignore($saleReturn?->id),
            ],
            'return_date' => ['sometimes', 'required', 'date'],
            'reference' => ['sometimes', 'nullable', 'string', 'max:255'],
            'note' => ['sometimes', 'nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'exists:sale_items,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }


    public function messages()
    {
        return [
            'sale_id.required' => 'Please select a sale.',
            'sale_id.exists' => 'The selected sale does not exist.',
            'return_date.required' => 'Return date is required.',
            'items.required' => 'At least one return item is required.',
            'items.min' => 'At least one return item is required.',
            'items.*.sale_item_id.required' => 'Sale item is required.',
            'items.*.sale_item_id.exists' => 'The selected sale item does not exist.',
            'items.*.quantity.required' => 'Return quantity is required.',
            'items.*.quantity.gt' => 'Return quantity must be greater than zero.',
            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.min' => 'Unit price cannot be negative.',
        ];
    }
}
