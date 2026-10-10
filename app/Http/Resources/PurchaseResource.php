<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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

            'purchase_no' => $this->purchase_no,
            'date' => $this->date,

            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'shipping' => (float) $this->shipping,
            'total' => (float) $this->total,

            'paid_amount' => (float) $this->paid_amount,
            'due_amount' => (float) $this->due_amount,

            'currency' => $this->currency,
            'status' => $this->status,
            'note' => $this->note,

            'created_by' => $this->created_by,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'store' => StoreResource::make(
                $this->whenLoaded('store')
            ),
            'supplier' => SupplierResource::make(
                $this->whenLoaded('supplier')
            ),

            'items_count' => $this->whenCounted('items'),
            'items' => PurchaseItemResource::collection(
                $this->whenLoaded('items')
            ),

            'creator' => UserResource::make(
                $this->whenLoaded('creator')
            ),
        ];
    }
}
