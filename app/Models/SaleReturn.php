<?php

namespace App\Models;

use App\Enums\Prefix;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->return_no) {
                $model->return_no = Prefix::SALE_RETURN->value . '-' . now()->format('Ymd-His');
            }
        });
    }
}
