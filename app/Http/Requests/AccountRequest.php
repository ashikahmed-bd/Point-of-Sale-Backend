<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
        $account = $this->route('account');

        return [
            'store_id' => ['required', 'exists:stores,id'],
            'name' => ['required', 'string', 'max:255',],
            'type' => ['nullable', 'string', 'max:50',],
            'account_no' => ['nullable', 'string', 'max:255',],
            'bank_name' => ['nullable', 'string', 'max:255',],

            'branch_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'opening_balance' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ];
    }
}
