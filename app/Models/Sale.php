<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sale extends Model
{
    use HasUlids;

    protected $guarded = [];

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }


    public function logs()
    {
        return $this->morphMany(Log::class, 'subject');
    }


    protected $casts = [
        'sale_date' => 'datetime',

        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'rounding' => 'decimal:2',
        'total' => 'decimal:2',

        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::creating(function ($sale) {
            if (!$sale->invoice_no) {
                $sale->invoice_no = now()->format('Ymd-His');
            }
        });
    }
}
