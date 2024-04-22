<?php

namespace App\Models;

use App\Models\Scopes\OwnerActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'post_id'
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
