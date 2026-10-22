<?php

namespace App\Models;

use App\Enums\Prefix;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
        'expected_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->purchase_no)) {
                $model->purchase_no = Prefix::PURCHASE->value . '-' . now()->format('Ymd-His');
            }
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
