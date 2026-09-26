<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasUlids;

    protected $guarded = [];


    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
