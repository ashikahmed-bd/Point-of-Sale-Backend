<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Variant extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'stock' => 'integer',
        'is_default' => 'boolean',
        'active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(VariantOption::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('/assets/products/default.svg');
        }

        return Storage::disk($this->disk)->url($this->image);
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
