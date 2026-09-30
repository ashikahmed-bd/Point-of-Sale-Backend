<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = app(CartService::class);

        return CartResource::make(
            $cart->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        $cart = app(CartService::class);

        $cart = $cart->add(
            $request->product_id,
            $request->variant_id,
            $request->quantity
        );

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'token' => $cart->token,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1',],
        ]);

        $cart = app(CartService::class);

        $cart = $cart->update(
            $item,
            $request->integer('quantity')
        );

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Cart updated successfully.',
        ]);
    }


    public function increment(
        Request $request,
        CartItem $item
    ) {
        $cart = app(CartService::class);

        $cart = $cart->increment(
            $item,
            $request->integer('quantity', 1)
        );

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Cart item quantity increased.',
        ]);
    }


    public function decrement(
        Request $request,
        CartItem $item
    ) {
        $cart = app(CartService::class);

        $cart = $cart->decrement(
            $item,
            $request->integer('quantity', 1)
        );

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Cart item quantity decreased.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $item)
    {
        $cart = app(CartService::class);

        $cart = $cart->remove($item);

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Cart item removed successfully.',
        ]);
    }

    public function clear()
    {
        $cart = app(CartService::class);

        $cart = $cart->clear();

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ]);
    }

    public function discount(Request $request)
    {
        $request->validate([
            'discount' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $cart = app(CartService::class);

        $cart = $cart->discount(
            $request->input('discount')
        );

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Discount updated successfully.',
        ]);
    }


    public function tax(Request $request)
    {
        $request->validate([
            'tax' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $cart = app(CartService::class);

        $cart = $cart->tax(
            $request->input('tax')
        );

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Tax updated successfully.',
        ]);
    }


    public function shipping(Request $request)
    {
        $request->validate([
            'shipping' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $cart = app(CartService::class);

        $cart = $cart->shipping(
            $request->input('shipping')
        );

        return CartResource::make($cart)->additional([
            'success' => true,
            'message' => 'Shipping updated successfully.',
        ]);
    }
}
