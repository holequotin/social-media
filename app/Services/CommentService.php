<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function __construct(
        protected CommentRepositoryInterface $commentRepository,
        protected FileService $fileService
    ) {
    }

    public function createComment($data)
    {
        $data['user_id'] = auth()->user()->id;
        $data = $this->addUrl($data);
        return $this->commentRepository->create($data);
    }

    public function deleteComment(Comment $comment)
    {

        try {
            DB::beginTransaction();
            $this->deleteImageComment($comment);
            $this->commentRepository->delete($comment->id);
            DB::commit();
        } catch (\Throwable $th) {
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
            $data = $this->addUrl($data);
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
        $urls = collect([$comment->url]);
        if ($comment->url) {
            $paths = ImageHelper::urlsToPaths($urls);
            $this->fileService->deleteImage($paths->all());
        }
    }
    /**
     * Add url to validated when validated have image
     * 
     * @param $validated
     * 
     * @return array
     */
    public function addUrl($validated)
    {
        if (isset($validated['image'])) {
            $urls = $this->fileService->storeImage('comments/'.auth()->user()->id, [$validated['image']]);
            $validated['url'] = $urls[0];
        }
        return $validated;
    }

    public function getCommentsByPost(Post $post)
    {
        return $this->commentRepository->getCommentsByPost($post);
    }
}   
