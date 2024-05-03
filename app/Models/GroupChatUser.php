<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_chat_id',
        'joined_at',
        'role'
    ];
}
