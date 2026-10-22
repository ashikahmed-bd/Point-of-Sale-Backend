<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnItemResource extends JsonResource
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
            'purchase_return' => PurchaseReturnResource::make(
                $this->whenLoaded('purchase_return')
            ),

            'product' => ProductResource::make(
                $this->whenLoaded('product')
            ),

            'variant' => VariantResource::make(
                $this->whenLoaded('variant')
            ),

            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'total' => $this->total,
            'reason' => $this->reason,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
