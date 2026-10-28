<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function lowStock(Request $request)
    {
        $query = Product::query()
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0);

        $products = (clone $query)
            ->with(['category.perent', 'brand'])
            ->when($request->keywords, function ($query, $keywords) {
                $query->where(function ($q) use ($keywords) {
                    $q->where('name', 'like', "%{$keywords}%")
                        ->orWhere('sku', 'like', "%{$keywords}%");
                });
            })
            ->latest()
            ->paginate($request->integer('limit', 15));



        return ProductResource::collection($products)->additional([
            'summary' => [
                'low_stock_products' => (clone $query)->count(),

                'critical_stock' => (clone $query)
                    ->whereRaw('stock <= min_stock * 0.5')
                    ->count(),

                'total_units_remaining' => (clone $query)
                    ->sum('stock'),

                'estimated_restock_cost' => round(
                    (clone $query)
                        ->get()
                        ->sum(fn($product) => ($product->min_stock - $product->stock) * $product->cost_price),
                    2
                ),
            ],
        ]);
    }
}
