<?php

namespace App\Helpers;

use App\Services\FileService;

class ImageHelper
{
    public static FileService $fileService;

    public static function urlsToPaths($urls)
    {
        $paths = $urls->map(function ($item, $key) {
            $path = parse_url($item, PHP_URL_PATH);
            return str_replace('storage', 'public', $path);
        });
        return $paths;
    }

    public static function addUrl($validated, $directory, $field)
    {
        if (isset($validated['image'])) {
            if (!isset(self::$fileService)) {
                self::$fileService = new FileService();
            }
            $urls = self::$fileService->storeImage($directory, [$validated['image']]);
            $validated[$field] = $urls[0];
        }
        return $validated;
    }
}
