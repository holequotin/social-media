<?php

namespace App\Models;

use App\Enums\FriendshipStatus;
use App\Enums\JoinGroupStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Friends who user send request
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'from_user_id', 'to_user_id');
    }

    /**
     * Friends who send request to user
     */
    public function isFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'to_user_id', 'from_user_id');
    }

    public function checkIsFriend($userId)
    {
        return $this->friends()->wherePivot('to_user_id', $userId)->wherePivot('status', FriendshipStatus::ACCEPTED)->exists() ||
            $this->isFriends()->wherePivot('from_user_id', $userId)->wherePivot('status', FriendshipStatus::ACCEPTED)->exists();
    }

    public function isInGroup(Group $group)
    {
        return $this->groups()->wherePivot('group_id', $group->id)
            ->wherePivot('status', JoinGroupStatus::JOINED)
            ->exists();
    }

    public function isWaitingAcceptGroup(Group $group)
    {
        return $this->groups()->wherePivot('group_id', $group->id)
            ->wherePivot('status', JoinGroupStatus::WAITING)
            ->exists();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }
}
