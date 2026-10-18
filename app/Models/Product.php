<?php

namespace App\Models;

use App\Models\Serial;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'track_serial' => 'boolean',
        'sold_count' => 'integer',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
        'has_variants' => 'boolean',
    ];

    public function scopeSummary(Builder $query): Builder
    {
        return $query->select([
            'id',
            'code',
            'name',
            'slug',
            'sku',
            'barcode',
            'cost_price',
            'price',
            'base_price',
            'currency',
            'min_stock',
            'stock',
            'track_serial',
            'sold_count',
            'has_variants',
            'cover',
        ]);
    }

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

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function serials()
    {
        return $this->hasMany(Serial::class);
    }

    public function availableSerials()
    {
        return $this->hasMany(Serial::class)
            ->where('status', 'in_stock')
            ->orderBy('serial_number');
    }

    public function getCoverUrlAttribute(): string
    {
        if (empty($this->cover)) {
            return asset('/assets/products/default.svg');
        }

        return Storage::disk($this->disk)->url($this->cover);
    }


    public function getGalleryUrlAttribute(): array
    {
        if (!is_array($this->gallery)) return [];

        return collect($this->gallery)
            ->map(fn($path) => Storage::disk($this->disk)->url($path))
            ->toArray();
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
