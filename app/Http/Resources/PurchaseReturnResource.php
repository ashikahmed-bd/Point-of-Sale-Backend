<?php

namespace App\Http\Resources;

use App\Http\Resources\PurchaseReturnItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReturnResource extends JsonResource
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
            'purchase' => PurchaseResource::make($this->whenLoaded('purchase')),

            'supplier' => SupplierResource::make($this->whenLoaded('supplier')),

            'return_no' => $this->return_no,
            'return_date' => $this->return_date,
            'reference' => $this->reference,

            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'shipping' => $this->shipping,
            'charge' => $this->charge,
            'total' => $this->total,

            'currency' => $this->currency,
            'status' => $this->status,
            'note' => $this->note,
            'created_by' => $this->created_by,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'items' => PurchaseReturnItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}
