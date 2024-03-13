<?php

namespace App\Repositories\Reaction;

use App\Models\Reaction;
use App\Repositories\BaseRepository;

class ReactionRepository extends BaseRepository implements ReactionRepositoryInterface
{
    public function getModel()
    {
        return Reaction::class;
    }

    /**
     * Get reaction by user_id and post_id
     * @param $userId
     * @param $postId
     */
    public function getReactionByUserPost($userId, $postId)
    {
        return $this->getModel()::where('user_id', $userId)
                                ->where('post_id', $postId)
                                ->first();
    }
}