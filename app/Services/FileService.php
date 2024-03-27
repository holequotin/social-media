<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Store images and return paths
     *
     * @param string $directory
     * @param array $images
     *
     * @return array
     */
    public function storeImage($directory, $images)
    {
        $paths = [];
        foreach ($images as $image) {
            $paths[] = Storage::putFileAs($directory, $image, $image->hashName());
        }
        return $paths;
    }

    /**
     * Delete images
     *
     * @param array $paths
     *
     * @return bool
     */
    public function deleteImage($paths = [])
    {
        return Storage::delete($paths);
    }

    public function deleteImageByPost($postId)
    {
        return Storage::deleteDirectory('public/posts/'.$postId);
    }
}
