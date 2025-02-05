<?php

namespace App\Models;

use App\Enums\GroupRole;
use App\Enums\GroupType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    use HasFactory;

    protected $with = ['owner', 'admins'];
    protected $fillable = [
        'name',
        'owner_id',
        'url',
        'type',
        'slug',
        'role'
    ];

    protected $attributes = [
        'type' => GroupType::PUBLIC
    ];

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id')->withPivot('status');
    }

    public function admins(): BelongsToMany
    {
        return $this->members()->wherePivot('role', GroupRole::ADMIN);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'group_id', 'id');
    }


}
