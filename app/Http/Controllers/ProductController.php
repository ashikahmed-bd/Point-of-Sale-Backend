<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
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
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
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
            ->paginate($request->integer('limit', 20));

        return ProductResource::collection($products);
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

            'min_stock' => $request->min_stock ?? 0,
            'max_stock' => $request->max_stock,

            'track_stock' => $request->track_stock ?? true,
            'allow_backorder' => $request->allow_backorder ?? false,
            'has_variants' => $request->has_variants ?? false,

            'status' => $request->status ?? 'active',

            'image' => $request->image,
            'gallery' => $request->gallery,
            'disk' => config('filesystems.default'),

            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'tax_id' => $request->tax_id,
            'unit_id' => $request->unit_id,

            'created_by' => $request->user()?->id,
        ]);

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
            'category:id,name',
            'brand:id,name',
            'tax:id,name',
            'unit:id,name',
        ]);

        ProductResource::make($product);
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
}
