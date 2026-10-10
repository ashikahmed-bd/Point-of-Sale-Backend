<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'price'      => 'decimal:2',
        'quantity'   => 'integer',
        'discount'   => 'decimal:2',
        'tax_rate'   => 'decimal:2',
        'tax'        => 'decimal:2',
        'subtotal'   => 'decimal:2',
        'total'      => 'decimal:2',
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
