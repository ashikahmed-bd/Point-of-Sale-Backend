<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleReturnRequest;
use App\Http\Resources\SaleReturnResource;
use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $returns = SaleReturn::with(['sale', 'items'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('return_no', 'like', "%{$search}%")
                        ->orWhereHas('sale', function ($saleQuery) use ($search) {
                            $saleQuery->where('sale_no', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($request->integer('limit', 15));

        return SaleReturnResource::collection($returns);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'return_date' => ['required', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'exists:sale_items,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $saleReturn = DB::transaction(function () use ($request) {
            $sale = Sale::with('items')
                ->lockForUpdate()
                ->findOrFail($request->input('sale_id'));

            $items = $request->input('items', []);
            $subtotal = 0;

            if (empty($items)) {
                throw ValidationException::withMessages([
                    'items' => 'At least one return item is required.',
                ]);
            }

            foreach ($items as $item) {
                $saleItem = $sale->items->firstWhere(
                    'id',
                    $item['sale_item_id'] ?? null
                );

                if (!$saleItem) {
                    throw ValidationException::withMessages([
                        'items' => 'The selected item does not belong to this sale.',
                    ]);
                }

                $quantity = (float) ($item['quantity'] ?? 0);
                $unitPrice = (float) ($item['unit_price'] ?? 0);

                if ($quantity <= 0 || $unitPrice < 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Invalid return quantity or unit price.',
                    ]);
                }

                $alreadyReturned = SaleReturn::where('sale_id', $sale->id)
                    ->with('items')
                    ->get()
                    ->flatMap(fn($return) => $return->items)
                    ->where('sale_item_id', $saleItem->id)
                    ->sum('quantity');

                $remaining = (float) $saleItem->quantity
                    - (float) $alreadyReturned;

                if ($quantity > $remaining) {
                    throw ValidationException::withMessages([
                        'items' => "Return quantity exceeds the remaining quantity for item {$saleItem->id}.",
                    ]);
                }

                $subtotal += $quantity * $unitPrice;
            }

            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'return_date' => $request->input('return_date'),
                'reference' => $request->input('reference'),
                'note' => $request->input('note'),
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            foreach ($items as $item) {
                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];

                $saleReturn->items()->create([
                    'sale_item_id' => $item['sale_item_id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $quantity * $unitPrice,
                ]);
            }

            return $saleReturn;
        });

        return SaleReturnResource::make($saleReturn)->additional([
            'success' => true,
            'message' => 'Sales return created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleReturn $saleReturn)
    {
        return SaleReturnResource::make($saleReturn->load(['sale', 'items']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaleReturnRequest $request, SaleReturn $saleReturn)
    {
        if ($request->exists('return_date')) {
            $saleReturn->return_date = $request->input('return_date');
        }

        if ($request->exists('reference')) {
            $saleReturn->reference = $request->input('reference');
        }

        if ($request->exists('note')) {
            $saleReturn->note = $request->input('note');
        }

        $saleReturn->save();

        return SaleReturnResource::make($saleReturn->fresh()->load(['sale', 'items']))->additional([
            'success' => true,
            'message' => 'Sales return updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleReturn $saleReturn)
    {
        DB::transaction(function () use ($saleReturn) {
            $saleReturn->items()->delete();
            $saleReturn->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Sales return deleted successfully.',
        ]);
    }
}
