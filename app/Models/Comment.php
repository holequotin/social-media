<?php

namespace App\Models;

use App\Models\Traits\BelongsToPost;
use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, BelongsToUser, BelongsToPost;

    protected $fillable = [
        'body',
        'user_id',  
        'url',
        'post_id'
    ];
}
