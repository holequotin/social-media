<?php

namespace App\Services;

use App\Repositories\Reaction\ReactionRepositoryInterface;

class ReactionService
{
    public function __construct(protected ReactionRepositoryInterface $reactionRepository) {
    }

    public function createReaction($data)
    {
        $data['user_id'] = auth()->id();
        return $this->reactionRepository->create($data);
    }

    public function updateReaction($id, $data)
    {
        return $this->reactionRepository->update($id,$data);
    }

    public function deleteReaction($id)
    {
        return $this->reactionRepository->delete($id);
    }

    public function getReactionsByPost($postId, $type)
    {
        return $this->reactionRepository->getReactionsByPost($postId, $type);
    }
}
