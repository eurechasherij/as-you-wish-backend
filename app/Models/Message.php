<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\MessageStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'content',
        'status',
        'displayed',
        'sender_id',
        'sender_type',
    ];

    protected $casts = [
        'status' => MessageStatusEnum::class,
        'displayed' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function sender(): MorphTo
    {
        return $this->morphTo();
    }
}
