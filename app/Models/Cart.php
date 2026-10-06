<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Cart $cart) {
            $cart->token ??= (string) Str::uuid();
        });

        static::saving(function (Cart $cart) {
            $cart->subtotal = $cart->items()->sum('subtotal');
            $cart->tax = $cart->items()->sum('tax');

            $cart->discount = max(0, (float) $cart->discount);
            $cart->shipping = max(0, (float) $cart->shipping);

            $cart->total = max(
                0,
                $cart->subtotal
                    + $cart->tax
                    + $cart->shipping
                    - $cart->discount
            );
        });
    }
}
