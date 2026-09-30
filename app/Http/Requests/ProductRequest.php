<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $product = $this->route('product');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:products,slug',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                'unique:products,sku',
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                'unique:products,barcode',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'cost_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'compare_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'min_stock' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_stock' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'track_stock' => [
                'boolean',
            ],

            'allow_backorder' => [
                'boolean',
            ],

            'has_variants' => [
                'boolean',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                    'draft',
                ]),
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'tax_id' => [
                'nullable',
                'exists:taxes,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'gallery' => [
                'nullable',
                'array',
            ],

            'gallery.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            /*
            |--------------------------------------------------------------------------
            | Variants
            |--------------------------------------------------------------------------
            */

            'variants' => [
                'nullable',
                'array',
            ],

            'variants.*.name' => [
                'required_with:variants',
                'string',
                'max:255',
            ],

            'variants.*.sku' => [
                'required_with:variants',
                'string',
                'max:255',
                'distinct',
            ],

            'variants.*.barcode' => [
                'nullable',
                'string',
                'max:255',
                'distinct',
            ],

            'variants.*.options' => [
                'nullable',
                'array',
            ],

            'variants.*.cost_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'variants.*.selling_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'variants.*.compare_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'variants.*.stock' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'variants.*.min_stock' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'variants.*.max_stock' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'variants.*.track_stock' => [
                'boolean',
            ],

            'variants.*.allow_backorder' => [
                'boolean',
            ],

            'variants.*.is_default' => [
                'boolean',
            ],

            'variants.*.status' => [
                'boolean',
            ],

            'variants.*.image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ?: str()->slug($this->name),
            'cost_price' => $this->cost_price ?? 0,
            'min_stock' => $this->min_stock ?? 0,
            'track_stock' => $this->boolean('track_stock', true),
            'allow_backorder' => $this->boolean('allow_backorder', false),
            'has_variants' => $this->boolean('has_variants', false),
            'status' => $this->status ?? 'active',
            'disk' => $this->disk ?? config('filesystems.default'),
        ]);
    }
}
