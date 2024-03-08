<?php
namespace App\Models\Traits;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyPost
{
    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }
}
