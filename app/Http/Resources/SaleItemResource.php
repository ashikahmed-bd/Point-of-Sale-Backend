<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
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
            'sku' => $this->sku,

            'cost_price' => $this->cost_price,
            'price' => $this->price,

            'quantity' => $this->quantity,

            'discount' => $this->discount,

            'tax_rate' => $this->tax_rate,
            'tax' => $this->tax,

            'subtotal' => $this->subtotal,
            'total' => $this->total,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
