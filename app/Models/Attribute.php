<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasUlids;

    protected $guarded = [];

    public function options()
    {
        return $this->hasMany(AttributeOption::class);
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
