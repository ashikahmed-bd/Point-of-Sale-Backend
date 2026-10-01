<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleRequest extends FormRequest
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
        $sale = $this->route('sale');

        return [
            'store_id' => [
                'required',
                'exists:stores,id',
            ],

            'customer_id' => [
                'nullable',
                'exists:customers,id',
            ],

            'account_id' => [
                'required',
                'exists:accounts,id',
            ],

            'invoice_no' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('sales', 'invoice_no')
                    ->ignore($sale?->id),
            ],

            'sale_date' => [
                'required',
                'date',
            ],

            'subtotal' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'shipping' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rounding' => [
                'nullable',
                'numeric',
            ],

            'total' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'due_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_status' => [
                'nullable',
                'string',
                'max:50',
            ],

            'status' => [
                'nullable',
                'string',
                'max:50',
            ],

            'note' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.variant_id' => [
                'nullable',
                'exists:variants,id',
            ],

            'items.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'items.*.sku' => [
                'required',
                'string',
                'max:255',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.001',
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.cost_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.total' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}
