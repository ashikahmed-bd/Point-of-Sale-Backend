<?php

namespace App\Http\Controllers;

use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    public function search(Request $request)
    {
        $stores = Store::query()
            ->when($request->input('keywords'), function ($query, $keywords) {
                $query->where('name', 'like', "%{$keywords}%");
            })
            ->select('*')
            ->limit(20)
            ->get();

        return StoreResource::collection($stores);
    }

    public function switch(Request $request, Store $store)
    {
        $user = $request->user();

        abort_unless(
            $user->stores()
                ->where('stores.id', $store->id)
                ->wherePivot('is_active', true)
                ->exists(),
            403
        );

        session(['store_id' => $store->id]);

        return response()->json([
            'success' => true,
            'message' => 'Store switched successfully.',
            'store' => $store,
        ]);
    }
}
