<?php

namespace App\Models;

use App\Events\CommentCreated;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function url(): Attribute
    {
        return Attribute::make(
            get: function($value) {
                if($value){
                    return config('app.url').$value;
                }
                return $value;
            }
        );
    }

    protected $dispatchesEvents = [
        'created' => CommentCreated::class                                                          
    ];

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
