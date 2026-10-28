<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class ProductSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,

            'name' => $this->name,
            'slug' => $this->slug,

            'sku' => $this->sku,
            'barcode' => $this->barcode,

            'cost_price' => $this->cost_price,
            'price' => $this->price,
            'base_price' => $this->base_price,
            'currency' => $this->currency,

            'min_stock' => $this->min_stock,
            'stock' => $this->stock,
            'track_serial' => $this->track_serial,
            'sold_count' => $this->sold_count < 1000 ? $this->sold_count
                : Number::abbreviate($this->sold_count, precision: 2),

            'cover_url' => $this->cover_url,

            'serials' => SerialResource::collection(
                $this->whenLoaded('availableSerials')
            ),
        ];
    }
}
