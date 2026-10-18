<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductSummaryResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->with([
                'category:id,name,slug',
                'brand:id,name,slug',
                'tax:id,name',
                'unit:id,name',
            ])
            ->when($request->filled('keywords'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->keywords}%")
                        ->orWhere('code', 'like', "%{$request->keywords}%")
                        ->orWhere('sku', 'like', "%{$request->keywords}%")
                        ->orWhere('barcode', 'like', "%{$request->keywords}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->brand_id, function ($query, $brandId) {
                $query->where('brand_id', $brandId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate($request->integer('limit', 10));

        $getStats = function ($query) {
            $count = (clone $query)->count();

            $lastMonth = (clone $query)
                ->whereBetween('created_at', [
                    now()->subMonth()->startOfMonth(),
                    now()->subMonth()->endOfMonth(),
                ])
                ->count();

            return [
                'count' => $count,
                'percentage' => $lastMonth > 0
                    ? round((($count - $lastMonth) / $lastMonth) * 100, 2)
                    : ($count > 0 ? 100 : 0),
            ];
        };

        return ProductResource::collection($products)->additional([
            'stats' => [
                'total' => $getStats(Product::query()),

                'low_stock' => $getStats(
                    Product::where('stock', '>', 0)->where('stock', '<=', 5)
                ),

                'out_of_stock' => $getStats(
                    Product::where('stock', '<=', 0)
                ),

                'active' => $getStats(
                    Product::where('status', 'active')
                ),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sku' => $request->sku,
            'barcode' => $request->barcode,

            'description' => $request->description,

            'cost_price' => $request->cost_price ?? 0,
            'selling_price' => $request->selling_price,
            'compare_price' => $request->compare_price,

            'stock' => $request->stock,
            'min_stock' => $request->min_stock ?? 0,
            'max_stock' => $request->max_stock,

            'track_stock' => $request->track_stock ?? true,
            'allow_backorder' => $request->allow_backorder ?? false,

            'status' => $request->status ?? 'active',

            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'tax_id' => $request->tax_id,
            'unit_id' => $request->unit_id,

            'created_by' => $request->user()?->id,
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('products', config('filesystems.default'));

            $product->update([
                'cover' => $path,
                'disk' => config('filesystems.default'),
            ]);
        }

        if ($request->hasFile('gallery')) {
            $gallery = collect($request->file('gallery'))
                ->map(fn($file) => $file->store('gallery', config('filesystems.default')))
                ->values()
                ->toArray();

            $product->update([
                'gallery' => $gallery,
            ]);
        }

        return ProductResource::make($product->fresh())->additional([
            'success' => true,
            'message' => 'Product created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load([
            'variants',
            'options.attribute',
            'options.option',
            'availableSerials',
        ]);

        return ProductSummaryResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest  $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'sku' => $request->sku,
            'barcode' => $request->barcode,

            'description' => $request->description,

            'cost_price' => $request->cost_price ?? 0,
            'selling_price' => $request->selling_price,
            'compare_price' => $request->compare_price,

            'min_stock' => $request->min_stock ?? 0,
            'max_stock' => $request->max_stock,

            'track_stock' => $request->track_stock ?? true,
            'allow_backorder' => $request->allow_backorder ?? false,
            'has_variants' => $request->has_variants ?? false,

            'status' => $request->status ?? 'active',

            'image' => $request->image,
            'gallery' => $request->gallery,
            'disk' => $request->disk ?? config('filesystems.default'),

            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'tax_id' => $request->tax_id,
            'unit_id' => $request->unit_id,
        ]);

        return ProductResource::make($product->fresh())->additional([
            'success' => true,
            'message' => 'Product updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }


    public function search(Request $request)
    {
        $products = Product::query()
            ->summary()
            ->with([
                'variants',
                'options.attribute',
                'options.option',
            ])
            ->withCount(['variants'])
            ->when($request->filled('keywords'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->keywords}%")
                        ->orWhere('code', 'like', "%{$request->keywords}%")
                        ->orWhere('sku', 'like', "%{$request->keywords}%")
                        ->orWhere('barcode', 'like', "%{$request->keywords}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->brand_id, function ($query, $brandId) {
                $query->where('brand_id', $brandId);
            })
            ->orderByDesc('sold_count')
            ->limit(20)
            ->get();

        return ProductSummaryResource::collection($products);
    }
}
