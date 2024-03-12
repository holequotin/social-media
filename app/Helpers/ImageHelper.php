<?php

namespace App\Helpers;

class ImageHelper
{
    public static function urlsToPaths($urls)
    {
        $paths = $urls->map(function ($item, $key) {
            $path = parse_url($item, PHP_URL_PATH);
            return str_replace('storage', 'public', $path);
        });
        return $paths;
    }
}
