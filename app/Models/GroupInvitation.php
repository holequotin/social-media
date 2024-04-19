<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'be_invite_id',
        'inviter_id',
        'group_id'
    ];

    protected $with = ['inviter', 'beInvite', 'group'];

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id', 'id');
    }

    public function beInvite(): BelongsTo
    {
        return $this->belongsTo(User::class, 'be_invite_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
