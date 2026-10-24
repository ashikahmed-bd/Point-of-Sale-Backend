<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->latest()
            ->paginate($request->integer('limit', 20));

        return SupplierResource::collection($suppliers)->additional([
            'summary' => [
                'total' => Supplier::count(),
                'active' => Supplier::where('is_active', true)->count(),
                'inactive' => Supplier::where('is_active', false)->count(),
                'total_balance' => Supplier::sum('balance'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $supplier = Supplier::create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'balance' => $request->balance ?? 0,
            'credit_limit' => $request->credit_limit,
            'notes' => $request->notes,
            'is_active' => $request->is_active ?? true,
        ]);

        return SupplierResource::make($supplier->refresh())->additional([
            'success' => true,
            'message' => 'Supplier created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return SupplierResource::make($supplier)->additional([
            'summary' => [
                'total_purchases' => (float) $supplier->purchases->sum('total'),
                'total_paid' => (float) $supplier->purchases->sum('paid_amount'),
                'outstanding_balance' => (float) $supplier->purchases->sum('due_amount'),
                'total_count' => $supplier->purchases->count(),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->postcode,
            'balance' => $request->balance ?? $supplier->balance,
            'credit_limit' => $request->credit_limit,
            'notes' => $request->notes,
            'is_active' => $request->is_active ?? $supplier->is_active,
        ]);

        return SupplierResource::make($supplier->refresh())->additional([
            'success' => true,
            'message' => 'Supplier updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully.',
        ]);
    }

    public function search(Request $request)
    {
        $suppliers = Supplier::query()
            ->when($request->input('keywords'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->limit(20)
            ->get();

        return SupplierResource::collection($suppliers);
    }


    public function enable(Supplier $supplier)
    {
        $supplier->update([
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier enabled successfully.',
        ]);
    }

    public function disable(Supplier $supplier)
    {
        $supplier->update([
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier disabled successfully.',
        ]);
    }
}
