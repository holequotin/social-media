<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'post_id'
    ];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => config('app.url').$value,
        );
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
