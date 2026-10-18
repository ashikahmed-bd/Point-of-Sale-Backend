<?php

namespace App\Models;

use App\Enums\Prefix;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasUlids;

    protected $guarded = [];


    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (!$model->return_no) {
                $model->return_no = Prefix::PURCHASE_RETURN->value
                    . '-' . now()->format('Ymd-His');
            }
        });
    }
}
