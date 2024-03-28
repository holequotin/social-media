<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class CommentService
{
    public function __construct(
        protected CommentRepositoryInterface $commentRepository,
        protected FileService $fileService
    ) {
    }

    public function createComment($data)
    {
        $data['user_id'] = auth()->id();
        $data = ImageHelper::addPath($data, 'comments/' . auth()->id(), 'url');
        return $this->commentRepository->create($data);
    }

    public function deleteComment(Comment $comment)
    {

        try {
            DB::beginTransaction();
            $this->deleteImageComment($comment);
            $this->commentRepository->delete($comment->id);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function updateComment(Comment $comment, $data)
    {
        if ($data['delete_image']) {
            $this->deleteImageComment($comment);
            $data['url'] = null;
        }
        if (isset($data['image'])) {
            $this->deleteImageComment($comment);
            $data = ImageHelper::addPath($data, 'comments/' . auth()->id(), 'url');
        }
        return $this->commentRepository->update($comment->id,$data);
    }
    /**
     * Delete image of comment
     *
     * @param $comment
     *
     * @return void
     */
    public function deleteImageComment($comment)
    {
        $paths = collect([$comment->url]);
        if ($comment->url) {
            $this->fileService->deleteImage($paths->all());
        }
    }

    public function getCommentsByPost(Post $post)
    {
        return $this->commentRepository->getCommentsByPost($post);
    }
}
