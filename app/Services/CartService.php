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
        $token = request()->header('X-Cart-Token') ?: (string) Str::uuid();

        return Cart::firstOrCreate(
            [
                'token' => $token,
                'store_id' => auth()->user()->store_id,
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
            ->load([
                'items.product:id,code,name,slug,sku,barcode,cover',
                'items.variant:id,name,sku,barcode,image',
            ]);
    }

    /**
     * Add product to cart.
     */
    public function add(string $product_id, ?string $variant_id, int $quantity, array $meta = []): Cart
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return DB::transaction(function () use ($product_id, $variant_id, $quantity, $meta) {
            $cart = $this->cart();

            $product = Product::query()
                ->with('tax')
                ->findOrFail($product_id);

            $variant = $variant_id
                ? Variant::query()
                ->where('product_id', $product->id)
                ->whereKey($variant_id)
                ->where('active', true)
                ->firstOrFail()
                : null;

            $price = (float) ($variant?->price ?? $product->price);
            $sku = $variant?->sku ?? $product->sku;

            $taxRate = $product->tax?->is_active
                ? (float) $product->tax->rate
                : 0;

            $item = $cart->items()
                ->where('product_id', $product->id)
                ->where('variant_id', $variant?->id)
                ->first();


            if ($item) {
                $item->quantity += $quantity;
                $item->meta = array_merge($item->meta ?? [], $meta);
                $item->tax_rate = $taxRate;
                $item->total = $item->price * $item->quantity;
                $item->save();
            } else {
                $item = $cart->items()->create([
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'name' => $product->name,
                    'sku' => $sku,
                    'price' => $price,
                    'quantity' => $quantity,
                    'tax_rate' => $taxRate,
                    'total' => $price * $quantity,
                    'meta' => $meta,
                ]);
            }

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,
                'discount' => 0,
                'shipping' => 0,
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
    public function update(CartItem $item, int $quantity, array $meta = []): Cart
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException(
                'Quantity must be at least 1.'
            );
        }

        return DB::transaction(function () use ($item, $quantity,  $meta) {
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

            $item->quantity = $quantity;
            $item->meta = array_merge($item->meta ?? [], $meta);
            $item->tax_rate = $taxRate;
            $item->total = $item->price * $item->quantity;
            $item->save();

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,
                'discount' => 0,
                'shipping' => 0,

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
     * Increase cart item.
     */
    public function increment(CartItem $item, int $quantity = 1): Cart
    {
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

            $item->total =  $item->price * $item->quantity;

            $item->save();

            $subtotal = $cart->items()->sum('total');

            $cart->update([
                'subtotal' => $subtotal,
                'discount' => 0,
                'shipping' => 0,

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
     * Decrease cart item.
     */
    public function decrement(CartItem $item, int $quantity = 1): Cart
    {
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
                'discount' => 0,
                'shipping' => 0,

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
                'discount' => 0,
                'shipping' => 0,

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

            return $cart->refresh()->load('items');
        });
    }
}
