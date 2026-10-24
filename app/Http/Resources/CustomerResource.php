<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'avatar_url' => $this->avatar_url,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postcode' => $this->postcode,
            'opening_balance' => $this->opening_balance,
            'credit_limit' => $this->credit_limit,
            'notes' => $this->notes,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            'sales_count' => $this->whenCounted('sales'),
        ];
    }
}
