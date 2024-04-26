<?php

namespace App\Models;

use App\Enums\PostType;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
        'type',
        'shared_post_id',
        'group_id'
    ];

    protected $attributes = [
        'type' => PostType::PUBLIC
    ];

    protected $with = ['group', 'user', 'images'];

    protected static function booted(): void
    {
        static::addGlobalScope('notBlocked', function ($builder) {
            $builder->whereNotIn('posts.user_id', function ($query) {
                $query->select('id')->from('users')
                    ->where('status', UserStatus::BLOCKED);
            });
        });
    }

    public function images() : HasMany
    {
        return $this->hasMany(PostImage::class,'post_id','id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions() : HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function sharedPost(): HasOne
    {
        return $this->hasOne(Post::class, 'id', 'shared_post_id')->with(['sharedPost', 'user', 'images']);
    }

    public function beSharedPosts(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'shared_post_id', 'id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
