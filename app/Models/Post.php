<?php

namespace App\Models;

use App\Enums\PostType;
use App\Models\Traits\HasManyComment;
use App\Models\Traits\HasManyPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, HasManyPost, HasManyComment;

    protected $fillable = [
        'body',
        'user_id',  
        'type'
    ];

    protected $attributes = [
        'type' => PostType::Public
    ];

    public function images() : HasMany
    {
        return $this->hasMany(PostImage::class,'post_id','id');
    }
}
