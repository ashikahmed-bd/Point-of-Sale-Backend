<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
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
            'product' => $this->whenLoaded('product'),

            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,

            'options' => $this->options,

            'cost_price' => $this->cost_price,
            'price' => $this->price,
            'base_price' => $this->base_price,

            'stock' => $this->stock,

            'image' => $this->image,
            'disk' => $this->disk,

            'is_default' => $this->is_default,
            'active' => $this->active,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
