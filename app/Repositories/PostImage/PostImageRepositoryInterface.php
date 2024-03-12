<?php
namespace App\Repositories\PostImage;

use App\Repositories\RepositoryInterface;

interface PostImageRepositoryInterface extends RepositoryInterface
{
    public function insert($values = []);
    public function getImageCountByPost($postId);
    public function destroy($postImageId = []);
    public function getUrlsById($postImageId = []);
    public function checkValidPostImage($postId, $postImageId = []);
    public function deletePostImageByPost($postId);
    public function getIdByPost($postId);
}
