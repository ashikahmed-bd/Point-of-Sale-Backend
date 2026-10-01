<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];


    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function variantOptions()
    {
        return $this->hasMany(VariantOption::class);
    }
}
