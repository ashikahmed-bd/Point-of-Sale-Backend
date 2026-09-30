<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Variant;
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
        return Cart::firstOrCreate(
            [
                'token' => request()->header('X-Cart-Token')
                    ?? (string) Str::uuid(),
            ],
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
    public function add(
        string $product_id,
        ?string $variant_id,
        int $quantity
    ): Cart {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return DB::transaction(function () use (
            $product_id,
            $variant_id,
            $quantity
        ) {
            $cart = $this->cart();

            $product = Product::query()
                ->with('tax')
                ->findOrFail($product_id);

            $variant = null;

            if ($variant_id) {
                $variant = Variant::query()
                    ->whereKey($variant_id)
                    ->where('product_id', $product->id)
                    ->firstOrFail();
            }

            $price = $variant?->price
                ?? $product->selling_price;

            if ($price === null) {
                throw new InvalidArgumentException(
                    'Product price is not available.'
                );
            }

            $taxRate = $product->tax?->is_active
                ? (float) $product->tax->rate
                : 0;

            $item = $cart->items()
                ->where('product_id', $product->id)
                ->where('variant_id', $variant?->id)
                ->first();

            if ($item) {
                $item->quantity += $quantity;

                $item->tax_rate = $taxRate;

                $item->total =
                    $item->price * $item->quantity;

                $item->save();
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,

                    'name' => $product->name,
                    'sku' => $variant?->sku
                        ?? $product->sku,

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

            return $cart
                ->refresh()
                ->load('items');
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
    public function discount(float $discount): Cart
    {
        if ($discount < 0) {
            throw new InvalidArgumentException(
                'Discount cannot be negative.'
            );
        }

        $cart = $this->cart();

        $cart->update([
            'discount' => $discount,
        ]);

        $cart->update([
            'total' => max(
                $cart->subtotal
                    - $cart->discount
                    + $cart->tax
                    + $cart->shipping,
                0
            ),
        ]);

        return $cart
            ->refresh()
            ->load('items');
    }

    /**
     * Set tax.
     */
    public function tax(float $tax): Cart
    {
        if ($tax < 0) {
            throw new InvalidArgumentException(
                'Tax cannot be negative.'
            );
        }

        $cart = $this->cart();

        $cart->update([
            'tax' => $tax,
        ]);

        $cart->update([
            'total' => max(
                $cart->subtotal
                    - $cart->discount
                    + $cart->tax
                    + $cart->shipping,
                0
            ),
        ]);

        return $cart
            ->refresh()
            ->load('items');
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

        $cart->update([
            'shipping' => $shipping,
        ]);

        $cart->update([
            'total' => max(
                $cart->subtotal
                    - $cart->discount
                    + $cart->tax
                    + $cart->shipping,
                0
            ),
        ]);

        return $cart
            ->refresh()
            ->load('items');
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
