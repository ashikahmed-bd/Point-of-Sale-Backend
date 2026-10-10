<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchases = Purchase::query()
            ->with(['store', 'supplier', 'items', 'creator'])
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
        $items = $request->items;

        return DB::transaction(function () use ($request, $items) {
            $subtotal = 0;
            $itemTax = 0;

            foreach ($items as $item) {
                $subtotal += $item['quantity'] * $item['unit_cost'];
                $itemTax += $item['tax'] ?? 0;
            }

            $discount = $request->discount ?? 0;
            $tax = $request->tax ?? $itemTax;
            $shipping = $request->shipping ?? 0;

            $total = $subtotal - $discount + $tax + $shipping;
            $paidAmount = $request->paid_amount ?? 0;

            if ($paidAmount > $total) {
                abort(422, 'Paid amount cannot exceed purchase total.');
            }

            $purchase = Purchase::create([
                'store_id' => $request->user()?->store_id,
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->date,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'due_amount' => $total - $paidAmount,
                'currency' => config('app.currency'),
                'status' => $request->status ?? 'draft',
                'note' => $request->note,
                'created_by' => $request->user()?->id,
            ]);

            foreach ($items as $item) {
                $quantity = $item['quantity'];
                $unitCost = $item['unit_cost'];
                $itemDiscount = $item['discount'] ?? 0;
                $itemTaxAmount = $item['tax'] ?? 0;

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'name' => $item['name'],
                    'sku' => $item['sku'],
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'discount' => $itemDiscount,
                    'tax' => $itemTaxAmount,
                    'total' => ($quantity * $unitCost) - $itemDiscount + $itemTaxAmount,
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
