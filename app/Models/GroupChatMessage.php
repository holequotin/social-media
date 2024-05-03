<?php

namespace App\Models;

use App\Events\GroupChatMessageCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'group_chat_id',
        'body'
    ];

    protected $with = ['user', 'groupChat'];

    protected $dispatchesEvents = [
        'created' => GroupChatMessageCreated::class
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function groupChat(): BelongsTo
    {
        return $this->belongsTo(GroupChat::class, 'group_chat_id', 'id');
    }
}
