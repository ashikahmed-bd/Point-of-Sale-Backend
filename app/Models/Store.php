<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasUlids;

    protected $guarded = [];


    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'is_default', 'is_active'])
            ->withTimestamps();
    }


    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
