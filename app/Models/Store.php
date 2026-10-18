<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasUlids;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean'
    ];


    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function account()
    {
        return $this->hasOne(Account::class)
            ->where('is_default', true);
    }
}
