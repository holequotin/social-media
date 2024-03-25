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
            ->with(['user'])
            ->first();
    }

    public function getReactionsByPost($postId, $type)
    {
        if ($type) {
            return $this->getModel()::where('post_id', $postId)
                ->where('type', $type)
                ->with(['user']);
        }
        return $this->getModel()::where('post_id', $postId)->with(['user']);
    }
}
