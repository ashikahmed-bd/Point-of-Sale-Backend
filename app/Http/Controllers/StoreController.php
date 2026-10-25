<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stores = Store::query()
            ->when($request->input('keywords'), function ($query, $keywords) {
                $query->where(function ($q) use ($keywords) {
                    $q->where('name', 'like', "%{$keywords}%")
                        ->orWhere('code', 'like', "%{$keywords}%")
                        ->orWhere('phone', 'like', "%{$keywords}%")
                        ->orWhere('email', 'like', "%{$keywords}%");
                });
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->latest()
            ->paginate($request->integer('limit', 15));

        return StoreResource::collection($stores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $store = Store::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'postcode' => $request->input('postcode'),
            'country' => $request->input('country', 'Bangladesh'),
            'is_active' => $request->input('is_active', true),
        ]);

        return StoreResource::make($store)->additional([
            'success' => true,
            'message' => 'Store created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        return StoreResource::make($store);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        $store->update([
            'name' => $request->input('name', $store->name),
            'code' => $request->input('code', $store->code),
            'phone' => $request->input('phone', $store->phone),
            'email' => $request->input('email', $store->email),
            'address' => $request->input('address', $store->address),
            'city' => $request->input('city', $store->city),
            'state' => $request->input('state', $store->state),
            'postcode' => $request->input('postcode', $store->postcode),
            'country' => $request->input('country', $store->country),
            'is_active' => $request->input('is_active', $store->is_active),
        ]);

        return StoreResource::make($store)->additional([
            'success' => true,
            'message' => 'Store updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        if ($store->is_active) {
            $store->update([
                'is_active' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Store deactivated successfully.',
            ]);
        }

        $store->delete();

        return response()->json([
            'success' => true,
            'message' => 'Store deleted successfully.',
        ]);
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
