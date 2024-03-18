<?php

namespace App\Models;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',  
        'type'
    ];

    protected $attributes = [
        'type' => PostType::PUBLIC
    ];

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
}
