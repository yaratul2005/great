<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\License;

class ApiController extends Controller {
    public function validate() {
        header('Content-Type: application/json');
        
        $key = $_GET['key'] ?? '';
        $secret = $_GET['product_secret'] ?? '';
        $domain = $_GET['domain'] ?? '';
        
        if (empty($key) || empty($secret)) {
            echo json_encode(['valid' => false, 'message' => 'License Key and Product Secret required']);
            exit;
        }
        
        $licenseModel = new License();
        $result = $licenseModel->validate($key, $secret, $domain);
        
        echo json_encode($result);
        exit;
    }
}
