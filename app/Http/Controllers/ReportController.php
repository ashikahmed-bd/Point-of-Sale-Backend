<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sales(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $startDate = request('start_date', now()->subDays(6)->toDateString());
        $endDate = request('end_date', now()->toDateString());

        $sales = Sale::where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate]);

        $totalSales = (clone $sales)->sum('total');

        $previousSales = Sale::where('status', 'completed')->whereBetween('date', [
            Carbon::parse($startDate)->subDays(
                Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate))
            )->toDateString(),
            Carbon::parse($startDate)->subDay()->toDateString(),
        ])
            ->sum('total');

        $growth = $previousSales > 0
            ? round((($totalSales - $previousSales) / $previousSales) * 100, 2)
            : 0;

        return response()->json([
            'summary' => [
                'total_sales' => $totalSales,
                'total_orders' => (clone $sales)->count(),
                'total_items_sold' => (clone $sales)->with('items')->get()
                    ->sum(fn($sale) => $sale->items->sum('quantity')),
                'average_order' => (clone $sales)->avg('total'),
                'overview' => [
                    'period' => 'last_7_days',
                    'total_sales' => $totalSales,
                    'growth_percentage' => $growth . '%',
                    'chart' => (clone $sales)
                        ->selectRaw('DATE(date) as date')
                        ->selectRaw('COUNT(*) as orders')
                        ->selectRaw('SUM(total) as sales')
                        ->groupByRaw('DATE(date)')
                        ->orderBy('date')
                        ->get(),
                ],
            ],


            'top_products' => [
                [
                    'product_id' => '01PRODUCT001',
                    'name' => 'Wireless Mouse',
                    'sku' => 'ACC-MOU-001',
                    'quantity_sold' => 35,
                    'total_sales' => 17500.00,
                ],
                [
                    'product_id' => '01PRODUCT002',
                    'name' => 'Mechanical Keyboard',
                    'sku' => 'ACC-KEY-001',
                    'quantity_sold' => 20,
                    'total_sales' => 30000.00,
                ],
                [
                    'product_id' => '01PRODUCT003',
                    'name' => 'USB Cable',
                    'sku' => 'ACC-CAB-001',
                    'quantity_sold' => 50,
                    'total_sales' => 7500.00,
                ],
            ],

            'recent_sales' => [
                [
                    'id' => '01SALE000001',
                    'sale_no' => 'SAL-20261025-001',
                    'customer' => 'Rahim Ahmed',
                    'date' => '2026-10-25T10:30:00',
                    'items_count' => 3,
                    'total' => 4500.00,
                    'paid_amount' => 4500.00,
                    'due_amount' => 0.00,
                    'status' => 'completed',
                ],
                [
                    'id' => '01SALE000002',
                    'sale_no' => 'SAL-20261025-002',
                    'customer' => 'Karim Hasan',
                    'date' => '2026-10-25T11:45:00',
                    'items_count' => 2,
                    'total' => 7500.00,
                    'paid_amount' => 5000.00,
                    'due_amount' => 2500.00,
                    'status' => 'partial',
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
