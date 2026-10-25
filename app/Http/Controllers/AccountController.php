<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accounts = Account::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('account_no', 'like', "%{$search}%")
                        ->orWhere('bank_name', 'like', "%{$search}%");
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->has('active'), function ($query) use ($request) {
                $query->where('active', $request->boolean('active'));
            })
            ->latest()
            ->paginate($request->integer('limit', 20));

        return AccountResource::collection($accounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountRequest $request)
    {
        $account = Account::create([
            'store_id' => $request->store_id,
            'name' => $request->name,
            'type' => $request->type ?? 'cash',
            'account_no' => $request->account_no,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'opening_balance' => $request->opening_balance ?? 0,
            'current_balance' => $request->opening_balance ?? 0,
            'active' => $request->active ?? true,
            'note' => $request->note,
        ]);

        return AccountResource::make($account->refresh())->additional([
            'success' => true,
            'message' => 'Account created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return AccountResource::make($account);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountRequest $request, Account $account)
    {
        $account->update([
            'store_id' => $request->store_id,
            'name' => $request->name,
            'type' => $request->type ?? $account->type,
            'account_no' => $request->account_no,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'opening_balance' => $request->opening_balance ?? $account->opening_balance,
            'active' => $request->active ?? $account->active,
            'note' => $request->note,
        ]);

        return AccountResource::make($account->refresh())->additional([
            'success' => true,
            'message' => 'Account updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
        ]);
    }

    public function search(Request $request)
    {
        $accounts = Account::query()
            ->when($request->input('keywords'), function ($query, $keywords) {
                $query->where(function ($q) use ($keywords) {
                    $q->where('name', 'like', "%{$keywords}%")
                        ->orWhere('number', 'like', "%{$keywords}%")
                        ->orWhere('name', 'like', "%{$keywords}%");
                });
            })
            ->when($request->input('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->has('active'), function ($query) use ($request) {
                $query->where('active', $request->boolean('active'));
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        return AccountResource::collection($accounts);
    }
}
