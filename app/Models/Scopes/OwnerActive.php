<?php

namespace App\Models\Scopes;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnerActive implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNotIn('user_id', function ($query) {
            $query->select('id')->from('users')
                ->where('status', UserStatus::BLOCKED);
        });
    }
}
