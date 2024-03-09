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
            $path = Storage::disk('local')->put('public/'.$directory,$image);
            $urls[] = config('app.url').Storage::url($path);
        }
        return $urls;
    }
}
