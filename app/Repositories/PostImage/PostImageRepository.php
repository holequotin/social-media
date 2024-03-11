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
}
