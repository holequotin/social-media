<?php

namespace App\Helpers;

use App\Services\FileService;

class ImageHelper
{
    public static FileService $fileService;

    public static function init()
    {
        if (!isset(self::$fileService)) {
            self::$fileService = new FileService();
        }
    }

    public static function addPath($validated, $directory, $field)
    {
        if (isset($validated['image'])) {
            self::init();
            $paths = self::$fileService->storeImage($directory, [$validated['image']]);
            $validated[$field] = $paths[0];
        }
        return $validated;
    }
}
