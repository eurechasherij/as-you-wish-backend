<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Guest extends Model
{
    protected $fillable = [
        'name',
        'email',
    ];

    public function userInfo(): MorphOne
    {
        return $this->morphOne(UserInfo::class, 'model');
    }
}
