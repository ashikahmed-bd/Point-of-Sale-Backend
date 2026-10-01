<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenses = Expense::query()
            ->with(['store', 'category', 'creator'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('expense_no', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->store_id, function ($query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($request->payment_method, function ($query, $paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            })
            ->when($request->from_date, function ($query, $date) {
                $query->whereDate('expense_date', '>=', $date);
            })
            ->when($request->to_date, function ($query, $date) {
                $query->whereDate('expense_date', '<=', $date);
            })
            ->latest('expense_date')
            ->paginate($request->integer('limit', 20));

        return ExpenseResource::collection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $expense = Expense::create([
            'store_id' => $request->store_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
            'created_by' => $request->user()?->id,
        ]);

        return ExpenseResource::make($expense->refresh())->additional([
            'success' => true,
            'message' => 'Expense created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['store', 'category', 'creator']);

        return ExpenseResource::make($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->update([
            'store_id' => $request->store_id,
            'category_id' => $request->category_id,
            'expense_no' => $request->expense_no ?? $expense->expense_no,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
        ]);

        return ExpenseResource::make($expense->refresh())->additional([
            'success' => true,
            'message' => 'Expense updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully.',
        ]);
    }
}
