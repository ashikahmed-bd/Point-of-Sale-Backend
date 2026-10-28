<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerialResource extends JsonResource
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
            'serial_number' => $this->serial_number,
            'imei' => $this->imei,

            'status' => $this->status,

            'purchase_date' => $this->purchase_date?->toDateString(),
            'warranty_start_date' => $this->warranty_start_date?->toDateString(),
            'warranty_end_date' => $this->warranty_end_date?->toDateString(),

            'condition' => $this->condition,
            'notes' => $this->notes,

            'store' => StoreResource::make(
                $this->whenLoaded('store')
            ),

            'product' => ProductResource::make(
                $this->whenLoaded('product')
            ),

            'purchase_item' => PurchaseItemResource::make(
                $this->whenLoaded('purchaseItem')
            ),

            'sale_item' => SaleItemResource::make(
                $this->whenLoaded('saleItem')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
