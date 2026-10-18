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

            'product' => $this->whenLoaded('product', fn() => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'slug' => $this->product->slug,
                'sku' => $this->product->sku,
                'barcode' => $this->product->barcode,
                'cover_url' => $this->product->cover_url,
            ]),
            'variant' => $this->whenLoaded('variant', fn() => [
                'id' => $this->variant->id,
                'name' => $this->variant->name,
                'sku' => $this->variant->sku,
                'barcode' => $this->variant->barcode,
                'image_url' => $this->variant->image_url,
            ]),

            'name' => $this->name,
            'sku' => $this->sku,
            'meta' => $this->meta,

            'price' => $this->price,
            'quantity' => $this->quantity,
            'tax_rate' => $this->tax_rate,
            'total' => $this->total,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
