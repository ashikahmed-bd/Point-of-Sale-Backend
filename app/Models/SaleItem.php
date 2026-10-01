<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:3',

        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',

        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];


    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}
