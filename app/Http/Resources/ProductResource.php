<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'code' => $this->code,

            'name' => $this->name,
            'slug' => $this->slug,

            'sku' => $this->sku,
            'barcode' => $this->barcode,

            'description' => $this->description,

            'cost_price' => $this->cost_price,
            'price' => $this->price,
            'base_price' => $this->base_price,
            'currency' => $this->currency,

            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,

            'track_stock' => $this->track_stock,
            'allow_backorder' => $this->allow_backorder,

            'has_variants' => $this->has_variants,
            'status' => $this->status,

            'cover_url' => $this->cover_url,
            'gallery_url' => $this->gallery_url,

            'category' => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),

            'brand' => $this->whenLoaded('brand', fn() => [
                'id'   => $this->brand->id,
                'name' => $this->brand->name,
                'slug' => $this->brand->slug,
            ]),

            'tax' => $this->whenLoaded('tax', fn() => [
                'id'   => $this->tax->id,
                'name' => $this->tax->name,
                'rate' => $this->tax->rate,
            ]),

            'unit' => $this->whenLoaded('unit', fn() => [
                'id'   => $this->unit->id,
                'name' => $this->unit->name,
            ]),

            'options' => collect(
                $this->variants
                    ->pluck('options')
                    ->filter()
                    ->reduce(function ($result, $options) {
                        foreach ($options as $name => $value) {
                            $result[$name] ??= [];

                            if (! in_array($value, $result[$name], true)) {
                                $result[$name][] = $value;
                            }
                        }

                        return $result;
                    }, [])
            )
                ->map(fn($values, $name) => [
                    'name' => $name,
                    'options' => array_values($values),
                ])
                ->values()
                ->all(),

            'variants' => VariantResource::collection(
                $this->whenLoaded('variants')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
