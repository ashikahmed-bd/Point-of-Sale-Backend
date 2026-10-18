<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchases = Purchase::query()
            ->with([
                'store',
                'supplier',
                'items',
                'creator:id,name,email,photo'
            ])
            ->withCount('items')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('purchase_no', 'like', "%{$search}%")
                        ->orWhereHas('supplier', function ($supplier) use ($search) {
                            $supplier->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('supplier_id'), function ($query) use ($request) {
                $query->where('supplier_id', $request->supplier_id);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('date', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('date', '<=', $request->to_date);
            })
            ->latest()
            ->paginate($request->integer('limit', 15));

        return PurchaseResource::collection($purchases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {

        return DB::transaction(function () use ($request) {

            if ($request->input('paid_amount') > $request->input('total')) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Paid amount cannot exceed the purchase total.',
                ]);
            }

            $purchase = Purchase::create([
                'store_id' => $request->input('store_id'),
                'supplier_id' => $request->input('supplier_id'),
                'reference' => $request->input('reference'),
                'purchase_date' => $request->input('purchase_date'),
                'expected_date' => $request->input('expected_date'),

                'subtotal' => $request->input('subtotal', 0),
                'discount' => $request->input('discount', 0),
                'tax' => $request->input('tax', 0),
                'shipping' => $request->input('shipping', 0),
                'total' => $request->input('total', 0),
                'paid_amount' => $request->input('paid_amount', 0),
                'due_amount' => $request->input('total', 0) - $request->input('paid_amount', 0),
                'currency' => $request->input('currency', 'BDT'),

                'status' => $request->input('status', 'draft'),

                'note' => $request->input('note'),
                'created_by' => $request->user()?->id,
            ]);


            foreach ($request->input('items', []) as $item) {
                $purchase->items()->create([
                    'product_id' => $item['id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'subtotal' => $item['subtotal'] ?? 0,
                    'total' => $item['total'] ?? 0,
                ]);
            }

            return PurchaseResource::make($purchase->refresh())->additional([
                'success' => true,
                'message' => 'Purchase created successfully.',
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load([
            'store',
            'supplier',
            'items',
            'creator',
        ])->loadCount('items');

        return new PurchaseResource($purchase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }

    public function recent(Request $request)
    {
        $purchases = Purchase::query()
            ->with(['supplier', 'items'])
            ->where('status', '!=', 'draft')
            ->latest()
            ->limit($request->integer('limit', 10))
            ->get();

        return response()->json($purchases);
    }

    public function drafts(Request $request)
    {
        $purchases = Purchase::query()
            ->with(['supplier', 'items'])
            ->where('status', 'draft')
            ->latest()
            ->paginate($request->integer('limit', 15));

        return response()->json($purchases);
    }
}
