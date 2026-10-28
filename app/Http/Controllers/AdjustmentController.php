<?php

namespace App\Http\Controllers;

use App\Enums\AdjustmentType;
use App\Http\Requests\AdjustmentRequest;
use App\Http\Resources\AdjustmentResource;
use App\Models\Adjustment;
use App\Models\AdjustmentItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $adjustments = Adjustment::query()
            ->where('store_id', $request->user()?->store_id)
            ->with('creator:id,name,email', 'items.product')
            ->withCount('items')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('adjustment_no', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate($request->integer('limit', 15));

        return AdjustmentResource::collection($adjustments)->additional([
            'summary' => [
                'total_adjustments' => $adjustments->total(),
                'stock_increased' => (float) AdjustmentItem::query()
                    ->whereHas(
                        'adjustment',
                        fn($query) => $query
                            ->where('status', 'completed')
                    )
                    ->where('type', 'increase')
                    ->sum('quantity'),

                'stock_decreased' => (float) AdjustmentItem::query()
                    ->whereHas(
                        'adjustment',
                        fn($query) => $query
                            ->where('status', 'completed')
                    )
                    ->whereIn('type', ['decrease', 'damaged', 'expired'])
                    ->sum('quantity'),

                'net_quantity_change' =>
                (float) AdjustmentItem::query()
                    ->whereHas(
                        'adjustment',
                        fn($query) => $query
                            ->where('status', 'completed')
                    )
                    ->where('type', 'increase')
                    ->sum('quantity')
                    -
                    (float) AdjustmentItem::query()
                        ->whereHas(
                            'adjustment',
                            fn($query) => $query
                                ->where('status', 'completed')
                        )
                        ->whereIn('type', ['decrease', 'damaged', 'expired'])
                        ->sum('quantity'),

            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdjustmentRequest $request)
    {
        $adjustment = DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create([
                'store_id' => $request->store_id,
                'reference' => $request->reference,
                'date' => $request->date,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'status' => $request->status ?? 'completed',
                'created_by' => $request->user()?->id,
            ]);

            foreach ($request->input('items', []) as $item) {
                $product = Product::query()
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $type = AdjustmentType::from($item['type']);
                $quantity = (float) $item['quantity'];
                $before = (float) $product->stock;

                $delta = match ($type) {
                    AdjustmentType::INCREASE => $quantity,
                    AdjustmentType::DECREASE,
                    AdjustmentType::DAMAGED,
                    AdjustmentType::EXPIRED => -$quantity,
                };

                $after = round($before + $delta, 3);

                if ($after < 0) {
                    throw ValidationException::withMessages([
                        'items' => ["Insufficient stock for product {$product->id}."],
                    ]);
                }

                $isCompleted = $adjustment->status === 'completed';

                if ($isCompleted) {
                    $product->stock = $after;
                    $product->save();
                }

                $adjustment->items()->create([
                    'product_id'      => $item['product_id'],
                    'type'            => $item['type'],
                    'quantity'        => $quantity,
                    'before_quantity' => $before,
                    'after_quantity'  => $isCompleted ? $after : $before,
                    'notes'           => $item['notes'] ?? null,
                ]);
            }

            return $adjustment;
        });

        return AdjustmentResource::make($adjustment->load('items'))->additional([
            'success' => true,
            'message' => 'Adjustment created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Adjustment $adjustment)
    {
        $adjustment->load(['creator', 'store', 'items.product']);
        return AdjustmentResource::make($adjustment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
