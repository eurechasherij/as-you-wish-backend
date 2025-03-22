<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserInfo extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'instagram',
        'twitter',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
