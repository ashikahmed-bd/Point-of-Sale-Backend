<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasUlids;

    protected $guarded = [];


    protected function casts(): array
    {
        return [
            'options' => 'array',
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'track_stock' => 'boolean',
            'allow_backorder' => 'boolean',
            'is_default' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
