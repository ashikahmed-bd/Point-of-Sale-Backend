<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sales = Sale::query()
            ->with(['store', 'customer', 'account'])
            ->withCount('items')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_no', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($customer) use ($search) {
                            $customer->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->store_id, function ($query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($request->customer_id, function ($query, $customerId) {
                $query->where('customer_id', $customerId);
            })
            ->when($request->account_id, function ($query, $accountId) {
                $query->where('account_id', $accountId);
            })
            ->when($request->payment_status, function ($query, $status) {
                $query->where('payment_status', $status);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->from_date, function ($query, $date) {
                $query->whereDate('sale_date', '>=', $date);
            })
            ->when($request->to_date, function ($query, $date) {
                $query->whereDate('sale_date', '<=', $date);
            })
            ->latest('sale_date')
            ->paginate($request->integer('limit', 20));

        return SaleResource::collection($sales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleRequest $request)
    {

        $sale = DB::transaction(function () use ($request) {

            $sale = Sale::create([
                'store_id' => $request->store_id,
                'customer_id' => $request->customer_id,
                'account_id' => $request->account_id,

                'sale_date' => $request->sale_date,

                'subtotal' => $request->subtotal ?? 0,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'shipping' => $request->shipping ?? 0,
                'rounding' => $request->rounding ?? 0,

                'total' => $request->total ?? 0,

                'paid_amount' => $request->paid_amount ?? 0,
                'due_amount' => $request->due_amount ?? 0,

                'payment_status' => $request->payment_status ?? 'paid',
                'status' => $request->status ?? 'completed',

                'note' => $request->note,

                'created_by' => $request->user()?->id,
            ]);

            foreach ($request->items as $item) {
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,

                    'name' => $item['name'],
                    'sku' => $item['sku'],

                    'quantity' => $item['quantity'],

                    'unit_price' => $item['unit_price'],
                    'cost_price' => $item['cost_price'],

                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'total' => $item['total'] ?? 0,
                ]);
            }

            return $sale;
        });


        // if ($product->track_stock) {
        //     if ($product->stock < $quantity) {
        //         throw ValidationException::withMessages([
        //             'quantity' => 'Insufficient stock.',
        //         ]);
        //     }

        //     $product->decrement('stock', $quantity);
        // }

        $sale->logs()->create([
            'store_id' => $sale->store_id,
            'user_id' => $request->user()?->id,
            'action' => 'created',
            'subject_type' => Sale::class,
            'subject_id' => $sale->id,
            'description' => 'Sale created.',
        ]);

        return SaleResource::make($sale->refresh())->additional([
            'success' => true,
            'message' => 'Sale created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load([
            'store',
            'customer',
            'account',
            'items',
        ]);

        return SaleResource::make($sale);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleRequest $request, Sale $sale)
    {
        DB::transaction(function () use ($request, $sale) {

            $sale->update([
                'store_id' => $request->store_id,
                'customer_id' => $request->customer_id,
                'account_id' => $request->account_id,

                'invoice_no' => $request->invoice_no
                    ?? $sale->invoice_no,

                'sale_date' => $request->sale_date,

                'subtotal' => $request->subtotal ?? 0,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'shipping' => $request->shipping ?? 0,
                'rounding' => $request->rounding ?? 0,

                'total' => $request->total ?? 0,

                'paid_amount' => $request->paid_amount ?? 0,
                'due_amount' => $request->due_amount ?? 0,

                'payment_status' => $request->payment_status
                    ?? $sale->payment_status,

                'status' => $request->status
                    ?? $sale->status,

                'note' => $request->note,
            ]);

            if ($request->has('items')) {

                $sale->items()->delete();

                foreach ($request->items as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'] ?? null,

                        'name' => $item['name'],
                        'sku' => $item['sku'],

                        'quantity' => $item['quantity'],

                        'unit_price' => $item['unit_price'],
                        'cost_price' => $item['cost_price'],

                        'discount' => $item['discount'] ?? 0,
                        'tax' => $item['tax'] ?? 0,
                        'total' => $item['total'] ?? 0,
                    ]);
                }
            }
        });

        return SaleResource::make($sale->refresh())->additional([
            'success' => true,
            'message' => 'Sale updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sale deleted successfully.',
        ]);
    }
}
