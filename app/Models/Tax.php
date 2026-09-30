<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasUlids;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
