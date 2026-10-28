<?php

namespace App\Models;

use App\Enums\Prefix;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adjustment extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AdjustmentItem::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->adjustment_no)) {
                $model->adjustment_no = Prefix::STOCK_ADJUSTMENT->value . '-' . now()->format('Ymd-His');
            }
        });
    }
}
