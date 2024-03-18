<?php

namespace App\Models;

use App\Enums\FriendshipStatus;
use App\Events\FriendshipCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_user_id',
        'from_user_id',
        'status'
    ];

    protected $attributes = [
        'status' => FriendshipStatus::PENDING
    ];

    protected $dispatchesEvents = [
        'created' => FriendshipCreated::class                                                          
    ];
}
