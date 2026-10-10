<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseItemResource extends JsonResource
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
            'purchase' => $this->whenLoaded('purchase'),
            'product' => $this->whenLoaded('product'),
            'variant' => $this->whenLoaded('variant'),

            'name' => $this->name,
            'sku' => $this->sku,

            'quantity' => (float) $this->quantity,
            'unit_cost' => (float) $this->unit_cost,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
