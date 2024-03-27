<?php

namespace App\Repositories\PostImage;

use App\Models\PostImage;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PostImageRepository extends BaseRepository implements PostImageRepositoryInterface
{
    public function getModel()
    {
        return PostImage::class;
    }

    public function insert($values = [])
    {
        return DB::table('post_images')->insert($values);
    }

    public function getImageCountByPost($postId)
    {
        return $this->getModel()::where('post_id',$postId)->count();
    }

    public function destroy($postImageId = [])
    {
        return $this->getModel()::destroy($postImageId);
    }

    public function getPathsById($postImageId = [])
    {
        return $this->getModel()::whereIn('id' , $postImageId)->pluck('url');
    }

    public function checkValidPostImage($postId, $postImageId = [])
    {
        $count = $this->getModel()::whereIn('id',$postImageId)
                    ->where('post_id',$postId)
                    ->count();

        return $count == count($postImageId);
    }

    public function getIdByPost($postId)
    {
        return $this->getModel()::where('post_id' , $postId)->pluck('id');
    }

    public function deletePostImageByPost($postId)
    {   
        return $this->getModel()::where('post_id',$postId)->delete();
    }
}
