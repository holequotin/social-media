<?php

namespace App\Repositories\Reaction;

use App\Repositories\RepositoryInterface;

interface ReactionRepositoryInterface extends RepositoryInterface
{
    public function getReactionByUserPost($userId, $postId);
}