<?php
namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasFriends
{
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'friendships','from_user_id','to_user_id');
    }   
}
