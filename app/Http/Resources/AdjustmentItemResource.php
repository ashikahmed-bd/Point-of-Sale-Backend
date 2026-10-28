<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentItemResource extends JsonResource
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
            'id'                => $this->id,
            'adjustment'     => AdjustmentResource::make($this->whenLoaded('adjustment')),

            'product'        => ProductResource::make(
                $this->whenLoaded('product')
            ),

            'type'              => $this->type,
            'quantity'          => $this->quantity,
            'after_quantity'    => $this->after_quantity,
            'before_quantity'   => $this->before_quantity,
            'notes'             => $this->notes,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
