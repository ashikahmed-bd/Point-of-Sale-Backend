<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Expense extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'datetime',
    ];


    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }


    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(
            ExpenseCategory::class,
            'category_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    protected static function booted()
    {
        static::creating(function ($expense) {
            if (!$expense->expense_no) {
                $expense->expense_no = 'EXP-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
            }
        });
    }
}
