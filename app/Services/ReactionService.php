<?php

namespace App\Services;

use App\Repositories\Reaction\ReactionRepositoryInterface;

class ReactionService
{
    public function __construct(protected ReactionRepositoryInterface $reactionRepository) {
    }

    public function createReaction($data)
    {
        $data['user_id'] = auth()->user()->id;
        return $this->reactionRepository->create($data);
    }
}