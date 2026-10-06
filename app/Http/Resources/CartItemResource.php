<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'cart' => CartResource::make($this->whenLoaded('cart')),
            'product' => ProductResource::make($this->whenLoaded('product')),
            'variant' => VariantResource::make($this->whenLoaded('variant')),

            'name' => $this->name,
            'sku' => $this->sku,

            'price' => $this->price,
            'quantity' => $this->quantity,
            'tax_rate' => $this->tax_rate,
            'total' => $this->total,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
