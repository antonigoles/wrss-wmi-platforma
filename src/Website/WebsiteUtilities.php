<?php

namespace App\Website;

class WebsiteUtilities
{
    public static function sanitize_redirect_url($url, $defaultUrl = '/') {
        if (empty($url)) {
            return $defaultUrl;
        }
        
        if (preg_match('/^\/[^\/]/', $url)) {
            return $url;
        }
        
        return $defaultUrl;
    }
}