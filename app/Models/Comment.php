<?php

namespace App\Models;

use App\Events\CommentCreated;
use App\Models\Scopes\OwnerActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
        'url',
        'post_id'
    ];

    protected $dispatchesEvents = [
        'created' => CommentCreated::class
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new OwnerActive);
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
