<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseReturnResource;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchaseReturns = PurchaseReturn::query()
            ->with([
                'purchase',
                'supplier',
                'items.product',
                'items.variant',
            ])
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('return_no', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%");
                });
            })
            ->when($request->input('supplier_id'), function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->when($request->input('purchase_id'), function ($query, $purchaseId) {
                $query->where('purchase_id', $purchaseId);
            })
            ->latest()
            ->paginate($request->integer('limit', 15));

        return PurchaseReturnResource::collection($purchaseReturns);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_id' => ['required', 'exists:purchases,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'return_date' => ['required', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'shipping' => ['nullable', 'numeric', 'min:0'],
            'charge' => ['nullable', 'numeric', 'min:0'],

            'note' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.variant_id' => ['nullable', 'exists:variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.tax' => ['nullable', 'numeric', 'min:0'],
            'items.*.reason' => ['nullable', 'string', 'max:255'],
        ]);

        $purchase = Purchase::query()->findOrFail($request->input('purchase_id'));

        $purchaseReturn = DB::transaction(function () use ($request, $purchase) {
            $purchaseReturn = PurchaseReturn::create([
                'purchase_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id,

                'return_date' => $request->input('return_date'),
                'reference' => $request->input('reference'),

                'subtotal' => $request->input('subtotal', 0),
                'discount' => $request->input('discount', 0),
                'tax' => $request->input('tax', 0),
                'shipping' => $request->input('shipping', 0),
                'other_charge' => $request->input('other_charge', 0),
                'total' => $request->input('total', 0),

                'currency' => $request->input(
                    'currency',
                    $purchase->currency ?? config('app.currency', 'BDT')
                ),

                'status' => 'draft',
                'note' => $request->input('note'),
            ]);

            foreach ($request->input('items', []) as $item) {
                $purchaseReturn->items()->create([
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'total' => $item['total'] ?? 0,
                    'reason' => $item['reason'] ?? null,
                ]);
            }

            return $purchaseReturn;
        });


        return PurchaseReturnResource::make($purchaseReturn->load([
            'purchase',
            'supplier',
            'items',
        ]))->additional([
            'success' => true,
            'message' => 'Purchase return created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseReturn $purchaseReturn)
    {
        return PurchaseReturnResource::make($purchaseReturn->load([
            'purchase',
            'supplier',
            'items',
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseReturn $purchaseReturn)
    {
        if ($purchaseReturn->status !== 'draft') {
            return response()->json([
                'message' => 'Only draft purchase returns can be updated.',
            ], 422);
        }

        $request->validate([
            'purchase_id' => ['sometimes', 'required', 'exists:purchases,id'],
            'return_date' => ['sometimes', 'required', 'date'],
            'reference' => ['sometimes', 'nullable', 'string', 'max:255'],

            'subtotal' => ['sometimes', 'required', 'numeric', 'min:0'],
            'discount' => ['sometimes', 'numeric', 'min:0'],
            'tax' => ['sometimes', 'numeric', 'min:0'],
            'shipping' => ['sometimes', 'numeric', 'min:0'],
            'other_charge' => ['sometimes', 'numeric', 'min:0'],
            'total' => ['sometimes', 'required', 'numeric', 'min:0'],

            'currency' => ['sometimes', 'string', 'size:3'],
            'note' => ['sometimes', 'nullable', 'string'],

            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.variant_id' => ['nullable', 'exists:variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.tax' => ['nullable', 'numeric', 'min:0'],
            'items.*.total' => ['required', 'numeric', 'min:0'],
            'items.*.reason' => ['nullable', 'string', 'max:255'],
        ]);

        if ($purchaseReturn->status !== 'draft') {
            return response()->json([
                'message' => 'Only draft purchase returns can be updated.',
            ], 422);
        }

        $purchase = $request->exists('purchase_id')
            ? Purchase::query()->findOrFail($request->input('purchase_id'))
            : $purchaseReturn->purchase;

        $purchaseReturn = DB::transaction(function () use ($request, $purchase, $purchaseReturn) {
            $purchaseReturn->update([
                'purchase_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id,

                'return_date' => $request->input('return_date', $purchaseReturn->return_date),
                'reference' => $request->input('reference', $purchaseReturn->reference),
                'subtotal' => $request->input('subtotal', $purchaseReturn->subtotal),
                'discount' => $request->input('discount', $purchaseReturn->discount),
                'tax' => $request->input('tax', $purchaseReturn->tax),
                'shipping' => $request->input('shipping', $purchaseReturn->shipping),
                'charge' => $request->input('charge', $purchaseReturn->other_charge),
                'total' => $request->input('total', $purchaseReturn->total),
                'note' => $request->input('note', $purchaseReturn->note),
            ]);

            if ($request->exists('items')) {
                $purchaseReturn->items()->delete();

                foreach ($request->input('items', []) as $item) {
                    $purchaseReturn->items()->create([
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'discount' => $item['discount'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'total' => $item['total'] ?? 0,
                        'reason' => $item['reason'] ?? null,
                    ]);
                }
            }

            return $purchaseReturn;
        });

        return PurchaseReturnResource::make($purchaseReturn->fresh()->load([
            'purchase',
            'supplier',
            'items',
        ]))->additional([
            'success' => true,
            'message' => 'Purchase return updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseReturn $purchaseReturn)
    {
        if ($purchaseReturn->status !== 'draft') {
            return response()->json([
                'message' => 'Only draft purchase returns can be deleted.',
            ], 422);
        }

        $purchaseReturn->delete();

        return response()->json([
            'message' => 'Purchase return deleted successfully.',
        ]);
    }
}
