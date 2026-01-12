<?php

namespace App\Config;

class App {
    // Detect base URL automatically
    public static function getBaseUrl() {
        // HARDCODED for PRODUCTION stability
        // Change this if you move domains
        return "https://great10.xyz";
        
        /* Auto-detection (Backup)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $scriptDir = str_replace('\\', '/', $scriptDir);
        $path = str_replace('/public', '', $scriptDir);
        return $protocol . $host . rtrim($path, '/');
        */
    }
}
