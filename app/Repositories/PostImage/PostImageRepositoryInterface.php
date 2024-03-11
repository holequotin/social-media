<?php
namespace App\Repositories\PostImage;

use App\Repositories\RepositoryInterface;

interface PostImageRepositoryInterface extends RepositoryInterface
{
    public function insert($values = []);
}
