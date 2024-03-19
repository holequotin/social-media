<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Store images and return urls
     * 
     * @param string $directory
     * @param array $images
     * 
     * @return array
     */
    public function storeImage($directory, $images)
    {
        $urls = [];
        foreach ($images as $image) {
            $extension = $image->getClientOriginalExtension();
            $path = Storage::disk('local')->putFileAs('public/' . $directory, $image, $image->hashName());
            $urls[] = Storage::url($path);
        }
        return $urls;
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
