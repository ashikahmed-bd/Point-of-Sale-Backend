<?php

namespace App\Http\Resources;

use App\Http\Resources\SaleItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'store' => $this->whenLoaded('store', fn() => [
                'id' => $this->store->id,
                'name' => $this->store->name,
                'code' => $this->store->code,
            ]),

            'customer' => CustomerResource::make(
                $this->whenLoaded('customer')
            ),

            'account' => $this->whenLoaded('account', fn() => [
                'id' => $this->account->id,
                'name' => $this->account->name,
            ]),

            'invoice_no' => $this->invoice_no,

            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'shipping' => $this->shipping,
            'total' => $this->total,

            'payable' => $this->payable,
            'change_amount' => $this->change_amount,
            'due_amount' => $this->due_amount,

            'currency' => $this->currency,

            'payment' => $this->payment,
            'status' => $this->status,

            'note' => $this->note,

            'created_by' => $this->created_by,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'items_count' => $this->items_count,

            'items' => SaleItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}
