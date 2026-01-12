<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\License;
use App\Models\Product;

class UserController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    public function dashboard() {
        $userId = $_SESSION['user_id'];
        $licenseModel = new License();
        $licenses = $licenseModel->getByUser($userId);
        
        $this->view('user/dashboard', ['licenses' => $licenses]);
    }

    public function purchase() {
        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            $this->redirect('/');
        }
        
        $userId = $_SESSION['user_id'];
        
        // In a real app, integrate Payment Gateway here.
        // For now, we simulate success for both Free and Paid.
        
        $licenseModel = new License();
        // Check if already owns? (Optional, skipping for simplicity to allow multi-license)
        
        $key = $licenseModel->generate($userId, $productId);
        
        if ($key) {
            // Also record order logic here if needed
            $this->redirect('/dashboard');
        } else {
            echo "Failed to generate license.";
        }
    }
}
