<?php

namespace App\Http\Resources;

use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'token' => $this->token,

            'store' => StoreResource::make(
                $this->whenLoaded('store')
            ),

            'customer' => CustomerResource::make(
                $this->whenLoaded('customer')
            ),

            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'shipping' => $this->shipping,
            'total' => $this->total,
            'currency' => $this->currency,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'items' => CartItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}
