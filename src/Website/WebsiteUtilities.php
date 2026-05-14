<?php

namespace App\Website;

class WebsiteUtilities
{
    public static function sanitize_redirect_url($url, $defaultUrl = '/') : string
    {
        if (empty($url)) {
            return $defaultUrl;
        }
        
        if (preg_match('/^\/[^\/]/', $url)) {
            return $url;
        }
        
        return $defaultUrl;
    }

    public static function get_asset_cache_stamp(string $filename): int
    {
        return filemtime(__DIR__ . '/../../public' . $filename) ?? 1;
    }
}