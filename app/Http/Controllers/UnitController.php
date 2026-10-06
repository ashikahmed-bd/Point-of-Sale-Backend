<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $units = Unit::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('short_name', 'like', "%{$search}%");
                });
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->latest()
            ->paginate($request->integer('limit', 20));

        return UnitResource::collection($units);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request)
    {
        $unit = Unit::create([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'is_decimal' => $request->is_decimal ?? false,
            'is_active' => $request->is_active ?? true,
        ]);

        return UnitResource::make($unit->refresh())->additional([
            'success' => true,
            'message' => 'Unit created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        return UnitResource::make($unit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'is_decimal' => $request->is_decimal ?? $unit->is_decimal,
            'is_active' => $request->is_active ?? $unit->is_active,
        ]);

        return UnitResource::make($unit->refresh())->additional([
            'success' => true,
            'message' => 'Unit updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unit deleted successfully.',
        ]);
    }

    public function search(Request $request)
    {
        $units = Unit::query()
            ->select('id', 'name', 'short_name')
            ->where('is_active', true)
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('short_name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return UnitResource::collection($units);
    }
}
