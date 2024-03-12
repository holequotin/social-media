<?php
namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\Comment;
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
        return $this->commentRepository->create($data);
    }

    public function deleteComment(Comment $comment)
    {
        $urls = collect([$comment->url]);
        try {
            DB::beginTransaction();
            $this->commentRepository->delete($comment->id);
            if($comment->url){
                $paths = ImageHelper::urlsToPaths($urls);
                $this->fileService->deleteImage($paths->all());
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
