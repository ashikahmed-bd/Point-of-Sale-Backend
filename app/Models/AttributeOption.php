<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeOption extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = [];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    // public function productOptions(): HasMany
    // {
    //     return $this->hasMany(ProductOption::class);
    // }

    // public function variantOptions(): HasMany
    // {
    //     return $this->hasMany(VariantOption::class);
    // }
}
