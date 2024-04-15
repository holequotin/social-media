<?php

namespace App\Models;

use App\Events\MessageCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $with = ['fromUser', 'toUser'];
    protected $fillable = [
        'body',
        'from_user_id',
        'to_user_id',
    ];
    protected $dispatchesEvents = [
        'created' => MessageCreated::class,
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }
}
