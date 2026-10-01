<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
        'has_variants' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id');
    }


    protected static function booted(): void
    {
        static::creating(function ($product) {
            do {
                $code = str()->padLeft((string) random_int(1, 9999999), 7, '0');
            } while (static::where('code', $code)->exists());

            $product->code = $code;
        });
    }
}
