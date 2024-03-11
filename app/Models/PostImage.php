<?php

namespace App\Models;

use App\Models\Traits\BelongsToPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory,BelongsToPost;

    protected $fillable = [
        'url',
        'post_id'
    ];
}
