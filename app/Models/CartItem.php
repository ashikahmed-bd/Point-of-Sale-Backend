<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'meta' => 'array',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    protected static function booted(): void
    {
        static::saving(function (CartItem $item) {
            $subtotal = (float) $item->price * (int) $item->quantity;

            $tax = $subtotal * ((float) $item->tax_rate / 100);

            $item->subtotal = $subtotal;
            $item->tax = $tax;
            $item->total = $subtotal + $tax;
        });

        static::saved(function (CartItem $item) {
            $item->cart?->save();
        });

        static::deleted(function (CartItem $item) {
            $item->cart?->save();
        });
    }
}
