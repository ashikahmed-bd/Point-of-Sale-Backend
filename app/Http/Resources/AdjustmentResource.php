<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustmentResource extends JsonResource
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
            'id'            => $this->id,
            'store'      => StoreResource::make($this->whenLoaded('store')),
            'adjustment_no' => $this->adjustment_no,
            'reference'     => $this->reference,
            'date'          => $this->date,
            'reason'        => $this->reason,
            'notes'         => $this->notes,
            'status'        => $this->status,
            'creator'    => $this->whenLoaded('creator', fn() => [
                'id'     => $this->creator->id,
                'name'     => $this->creator->name,
                'email'     => $this->creator->email,
            ]),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,

            'items_count'    => $this->whenCounted('items'),
            'items' => AdjustmentItemResource::collection(
                $this->whenLoaded('items')
            ),
        ];
    }
}
