<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxRequest;
use App\Http\Resources\TaxResource;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $taxes = Tax::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->latest()
            ->paginate($request->integer('limit', 20));

        return TaxResource::collection($taxes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxRequest $request)
    {
        $tax = Tax::create([
            'name' => $request->name,
            'rate' => $request->rate ?? 0,
            'type' => $request->type ?? 'percentage',
            'is_active' => $request->is_active ?? true,
        ]);

        return TaxResource::make($tax->refresh())->additional([
            'success' => true,
            'message' => 'Tax created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax)
    {
        return TaxResource::make($tax);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxRequest $request, Tax $tax)
    {
        $tax->update([
            'name' => $request->name,
            'rate' => $request->rate ?? $tax->rate,
            'type' => $request->type ?? $tax->type,
            'is_active' => $request->is_active ?? $tax->is_active,
        ]);

        return TaxResource::make($tax->refresh())->additional([
            'success' => true,
            'message' => 'Tax updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        $tax->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tax deleted successfully.',
        ]);
    }


    public function search(Request $request)
    {
        $taxes = Tax::query()
            ->select('id', 'name', 'rate', 'type')
            ->where('is_active', true)
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return TaxResource::collection($taxes);
    }
}
