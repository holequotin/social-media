<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupChatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_chat_id',
        'joined_at',
        'role'
    ];

    protected $with = ['user', 'groupChat'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function groupChat(): BelongsTo
    {
        return $this->belongsTo(GroupChat::class, 'group_chat_id', 'id');
    }
}
