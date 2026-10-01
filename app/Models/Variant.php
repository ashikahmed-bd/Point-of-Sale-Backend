<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
        'is_default' => 'boolean',
        'active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Variant $variant) {
            if (blank($variant->barcode)) {
                do {
                    $variant->barcode = '8901' . str_pad(
                        (string) random_int(0, 99999999),
                        8,
                        '0',
                        STR_PAD_LEFT
                    );
                } while (
                    static::where('barcode', $variant->barcode)->exists()
                );
            }
        });
    }
}
