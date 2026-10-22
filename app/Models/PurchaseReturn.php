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

            $model->total = max(
                0,
                (float) ($model->subtotal ?? 0)
                    - (float) ($model->discount ?? 0)
                    + (float) ($model->tax ?? 0)
                    + (float) ($model->shipping ?? 0)
                    + (float) ($model->charge ?? 0)
            );
        });

        static::saving(function ($model) {
            $model->total = max(
                0,
                (float) $model->subtotal
                    - (float) $model->discount
                    + (float) $model->tax
                    + (float) $model->shipping
                    + (float) $model->charge
            );
        });
    }
}
