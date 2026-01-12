<?php

use App\Config\App;

if (!function_exists('url')) {
    function url($path = '') {
        // Remove leading slash to avoid double slashes with base
        $path = ltrim($path, '/');
        return App::getBaseUrl() . '/' . $path;
    }
}

if (!function_exists('asset')) {
    function asset($path = '') {
        $path = ltrim($path, '/');
        // Assets are in public folder.
        // If access via /great/ (rewritten to public), then specific asset path might need 'assets/' check
        // Or simply /great/assets/...
        return App::getBaseUrl() . '/' . $path;
    }
}
