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

    public function profile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $_SESSION['user_id'];
        $userModel = new \App\Models\User();
        $user = $userModel->getUserById($userId);
        
        $this->view('user/profile', ['user' => $user]);
    }

    public function updateProfile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            
            $userModel = new \App\Models\User();
            if ($userModel->updateProfile($userId, $name, $email)) {
                // Update Session Name
                $_SESSION['user_name'] = $name;
                $this->redirect('/profile?success=profile_updated');
            } else {
                $this->redirect('/profile?error=update_failed');
            }
        }
    }

    public function updatePassword() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $newPassword = $_POST['new_password'];
            
            // Should validate confirm password etc, but keep simple
            $userModel = new \App\Models\User();
            if ($userModel->updatePassword($userId, $newPassword)) {
                $this->redirect('/profile?success=password_updated');
            } else {
                $this->redirect('/profile?error=update_failed');
            }
        }
    }

    public function invoices() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $_SESSION['user_id'];
        $orderModel = new \App\Models\Order();
        $orders = $orderModel->getByUser($userId);
        
        $this->view('user/invoices', ['orders' => $orders]);
    }

    public function downloadInvoice() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            die("Order ID required");
        }
        
        $userId = $_SESSION['user_id'];
        $orderModel = new \App\Models\Order();
        $order = $orderModel->getById($orderId);
        
        if (!$order || $order['user_id'] != $userId) {
            die("Invoice not found or access denied.");
        }
        
        // Render print view (no header/footer)
        extract(['order' => $order]);
        require_once dirname(__DIR__) . '/Views/user/invoice_print.php';
    }
}
