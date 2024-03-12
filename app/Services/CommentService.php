<?php
namespace App\Services;

use App\Repositories\Comment\CommentRepositoryInterface;

class CommentService
{
    public function __construct(protected CommentRepositoryInterface $commentRepository) {
    }

    public function createComment($data)
    {
        $data['user_id'] = auth()->user()->id;
        return $this->commentRepository->create($data);
    }
}
