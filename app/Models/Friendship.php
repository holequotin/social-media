<?php

namespace App\Models;

use App\Enums\FriendshipStatus;
use App\Events\FriendshipCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_user_id',
        'from_user_id',
        'status',
        'from_user_nickname',
        'from_user_nickname',
    ];

    protected $attributes = [
        'status' => FriendshipStatus::PENDING
    ];

    protected $dispatchesEvents = [
        'created' => FriendshipCreated::class
    ];

    public function fromUser() : BelongsTo
    {
        return $this->belongsTo(User::class,'from_user_id', 'id');
    }

    public function toUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }
}
