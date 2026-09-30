<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            ->with([
                'parent:id,name',
            ])
            ->withCount('products')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->parent_id, function ($query, $parent_id) {
                $query->where('parent_id', $parent_id);
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate($request->integer('limit', 20));

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'disk' => config('filesystems.default'),
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', config('filesystems.default'));

            $category->update([
                'image' => $path,
                'disk' => config('filesystems.default'),
            ]);
        }

        return CategoryResource::make($category->refresh())->additional([
            'success' => true,
            'message' => 'Category created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('parent');

        $category->loadCount('products');

        return CategoryResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'disk' => config('filesystems.default'),
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->hasFile('image ')) {
            if (Storage::disk($category->disk)->exists($category->image)) {
                Storage::disk($category->disk)->delete($category->image);
            }

            $path = $request->file('image')->store('brands', config('filesystems.default'));

            $category->update([
                'image' => $path,
                'disk' => config('filesystems.default'),
            ]);
        }

        return CategoryResource::make($category->fresh())->additional([
            'success' => true,
            'message' => 'Category updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        if (Storage::disk($category->disk)->exists($category->image)) {
            Storage::disk($category->disk)->delete($category->image);
        }

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
