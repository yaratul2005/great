<?php

namespace App\Config;

class App {
    // Detect base URL automatically
    public static function getBaseUrl() {
        // Simple detection for protocol
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        
        // Detect script path (subdirectory)
        // If script is at /great/public/index.php, we want /great
        // If script is at /index.php, we want / (empty)
        
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        // Normalize
        $scriptDir = str_replace('\\', '/', $scriptDir);
        
        // If public is in the path, remove it to get app root URL for user facing links IF we are rewriting to public
        // BUT, our Current structure is:
        // /great/ -> redirects to /great/public/ -> index.php routes relative to internal
        
        // Actually, easiest way for XAMPP + Rewrite is:
        // We want the URL that points to the folder containing .htaccess
        // which defaults to just "/" or "/great/"
        
        // Let's hardcode for now to ensure stability if auto-detection is flaky, 
        // OR better: use relative base.
        
        // Let's try to detect the "public" part and strip it if we are hiding it via htaccess
        // But since we are using /great/ in browser, that's our base.
        
        $path = str_replace('/public', '', $scriptDir);
        return $protocol . $host . rtrim($path, '/');
    }
}
