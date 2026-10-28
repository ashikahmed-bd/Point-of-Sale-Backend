<?php

namespace App\Http\Requests;

use App\Enums\AdjustmentType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustmentRequest extends FormRequest
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
        return [
            'store_id' => ['required', 'string', 'exists:stores,id'],
            'reference' => ['nullable', 'string', 'max:50', 'unique:adjustments,reference'],
            'date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['draft', 'completed'])],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'ulid', 'distinct', 'exists:products,id'],
            'items.*.type' => ['required', Rule::enum(AdjustmentType::class)],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.notes' => ['nullable', 'string'],
        ];
    }
}
