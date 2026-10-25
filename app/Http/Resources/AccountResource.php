<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'store' => StoreResource::make(
                $this->whenLoaded('store')
            ),
            'name' => $this->name,
            'number' => $this->number,
            'bank' => $this->bank,
            'branch' => $this->branch,
            'opening_balance' => $this->opening_balance,
            'current_balance' => $this->current_balance,
            'currency' => $this->currency,
            'is_default' => $this->is_default,
            'active' => $this->active,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
