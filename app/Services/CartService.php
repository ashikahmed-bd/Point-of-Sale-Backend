<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CartService
{
    /**
     * Get or create cart.
     */
    private function cart(): Cart
    {
        $token = request()->header('X-Cart-Token') ?: (string) Str::uuid();

        return Cart::firstOrCreate(
            ['token' => $token],
            [
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'shipping' => 0,
                'total' => 0,
            ]
        );
    }

    /**
     * Get cart.
     */
    public function get(): Cart
    {
        return $this->cart()
            ->load('items');
    }

    /**
     * Add product to cart.
     */
    public function add(string $product_id, int $quantity): Cart
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return DB::transaction(function () use ($product_id, $quantity) {
            $cart = $this->cart();

            $product = Product::query()
                ->with('tax')
                ->findOrFail($product_id);

            $price = (float) $product->price;

            $taxRate = $product->tax?->is_active
                ? (float) $product->tax->rate
                : 0;

            $item = $cart->items()
                ->where('product_id', $product->id)
                ->first();

            if ($item) {
                $item->quantity += $quantity;
                $item->tax_rate = $taxRate;
                $item->total = $item->price * $item->quantity;
                $item->save();
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $price,
                    'quantity' => $quantity,
                    'tax_rate' => $taxRate,
                    'total' => $price * $quantity,
                ]);
            }

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,
                'total' => max(
                    $subtotal
                        - $cart->discount
                        + $cart->tax
                        + $cart->shipping,
                    0
                ),
            ]);

            return $cart->refresh()->load('items');
        });
    }

    /**
     * Update cart item.
     */
    public function update(
        CartItem $item,
        int $quantity
    ): Cart {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return DB::transaction(function () use (
            $item,
            $quantity
        ) {
            $cart = $this->cart();

            if ($item->cart_id !== $cart->id) {
                throw new InvalidArgumentException(
                    'Cart item does not belong to this cart.'
                );
            }

            $product = Product::query()
                ->with('tax')
                ->findOrFail($item->product_id);

            $taxRate = $product->tax?->is_active
                ? (float) $product->tax->rate
                : 0;

            $item->update([
                'quantity' => $quantity,
                'tax_rate' => $taxRate,
                'total' => $item->price * $quantity,
            ]);

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,

                'total' => max(
                    $subtotal
                        - $cart->discount
                        + $cart->tax
                        + $cart->shipping,
                    0
                ),
            ]);

            return $cart
                ->refresh()
                ->load('items');
        });
    }

    /**
     * Increase cart item.
     */
    public function increment(
        CartItem $item,
        int $quantity = 1
    ): Cart {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return DB::transaction(function () use (
            $item,
            $quantity
        ) {
            $cart = $this->cart();

            if ($item->cart_id !== $cart->id) {
                throw new InvalidArgumentException(
                    'Cart item does not belong to this cart.'
                );
            }

            $item->quantity += $quantity;

            $item->total =
                $item->price * $item->quantity;

            $item->save();

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,

                'total' => max(
                    $subtotal
                        - $cart->discount
                        + $cart->tax
                        + $cart->shipping,
                    0
                ),
            ]);

            return $cart
                ->refresh()
                ->load('items');
        });
    }

    /**
     * Decrease cart item.
     */
    public function decrement(
        CartItem $item,
        int $quantity = 1
    ): Cart {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return DB::transaction(function () use (
            $item,
            $quantity
        ) {
            $cart = $this->cart();

            if ($item->cart_id !== $cart->id) {
                throw new InvalidArgumentException(
                    'Cart item does not belong to this cart.'
                );
            }

            $item->quantity -= $quantity;

            if ($item->quantity <= 0) {
                $item->delete();
            } else {
                $item->total =
                    $item->price * $item->quantity;

                $item->save();
            }

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,

                'total' => max(
                    $subtotal
                        - $cart->discount
                        + $cart->tax
                        + $cart->shipping,
                    0
                ),
            ]);

            return $cart
                ->refresh()
                ->load('items');
        });
    }

    /**
     * Remove cart item.
     */
    public function remove(CartItem $item): Cart
    {
        return DB::transaction(function () use ($item) {
            $cart = $this->cart();

            if ($item->cart_id !== $cart->id) {
                throw new InvalidArgumentException(
                    'Cart item does not belong to this cart.'
                );
            }

            $item->delete();

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,

                'total' => max(
                    $subtotal
                        - $cart->discount
                        + $cart->tax
                        + $cart->shipping,
                    0
                ),
            ]);

            return $cart
                ->refresh()
                ->load('items');
        });
    }

    /**
     * Set discount.
     */
    public function discount(float $discount, string $type): Cart
    {
        if ($discount < 0) {
            throw new InvalidArgumentException(
                'Discount cannot be negative.'
            );
        }

        $cart = $this->cart();

        $discount = $type === 'percent'
            ? ($cart->subtotal * $discount) / 100
            : $discount;

        $discount = min($discount, $cart->subtotal);

        $cart->discount = $discount;
        $cart->save();

        return $cart->refresh()->load('items');
    }


    /**
     * Set shipping.
     */
    public function shipping(float $shipping): Cart
    {
        if ($shipping < 0) {
            throw new InvalidArgumentException(
                'Shipping cannot be negative.'
            );
        }

        $cart = $this->cart();

        $cart->shipping = $shipping;
        $cart->save();

        return $cart->refresh()->load('items');
    }

    /**
     * Clear cart.
     */
    public function clear(): Cart
    {
        return DB::transaction(function () {
            $cart = $this->cart();

            $cart->items()->delete();

            $cart->update([
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'shipping' => 0,
                'total' => 0,
            ]);

            return $cart
                ->refresh()
                ->load('items');
        });
    }
}
