<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->latest()
            ->paginate($request->integer('limit', 20));

        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest  $request)
    {
        $brand = Brand::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', config('filesystems.default'));

            $brand->update([
                'logo' => $path,
                'disk' => config('filesystems.default'),
            ]);
        }

        return BrandResource::make($brand->refresh())->additional([
            'success' => true,
            'message' => 'Brand created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return BrandResource::make($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        $brand->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->is_active ?? $brand->is_active,
        ]);

        if ($request->hasFile('logo')) {
            if (Storage::disk($brand->disk)->exists($brand->logo)) {
                Storage::disk($brand->disk)->delete($brand->logo);
            }

            $path = $request->file('logo')->store('brands', config('filesystems.default'));

            $brand->update([
                'logo' => $path,
                'disk' => config('filesystems.default'),
            ]);
        }

        return BrandResource::make($brand->refresh())->additional([
            'success' => true,
            'message' => 'Brand updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        if (Storage::disk($brand->disk)->exists($brand->logo)) {
            Storage::disk($brand->disk)->delete($brand->logo);
        }

        return response()->json([
            'message' => 'Brand deleted successfully.',
        ]);
    }


    public function search(Request $request)
    {
        $brands = Brand::query()
            ->select('id', 'name')
            ->where('is_active', true)
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return BrandResource::collection($brands);
    }
}
