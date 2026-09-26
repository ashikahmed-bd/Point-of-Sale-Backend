<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

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
}
