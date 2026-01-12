<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class AdminController extends Controller {
    
    public function __construct() {
        // Enforce Admin Access
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
    }

    public function dashboard() {
        $productModel = new Product();
        $products = $productModel->getAll();
        
        $this->view('admin/dashboard', ['products' => $products]);
    }
    
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check for POST size limit exceeded
            if (empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
                 $maxSize = ini_get('post_max_size');
                 die("Error: File size exceeds the server limit of $maxSize. Please increase post_max_size and upload_max_filesize in php.ini.");
            }

            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $type = $_POST['type'] ?? 'free';
            $version = $_POST['version'] ?? '1.0';
            $externalLink = $_POST['external_link'] ?? null;
            
            // File Upload logic
            $relativePath = null;
            if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
                $targetDir = dirname(__DIR__, 2) . "/public/uploads/products/";
                if (!is_dir($targetDir)) {
                    if (!mkdir($targetDir, 0755, true)) {
                        die("Error: Failed to create upload directory: " . $targetDir);
                    }
                }
                
                $fileName = basename($_FILES["file"]["name"]);
                $uniqueName = uniqid() . "_" . $fileName;
                $targetFilePath = $targetDir . $uniqueName;
                
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                    $relativePath = "uploads/products/" . $uniqueName;
                } else {
                     $uploadError = $_FILES["file"]["error"];
                     die("Error: File upload failed. Move_uploaded_file return false.");
                }
            } 
            
            if (!$relativePath && !$externalLink) {
                 die("Error: Please provide either a file or an external download link.");
            }
            
            $productModel = new Product();

            try {
                if ($productModel->create($title, $description, $price, $type, $relativePath, $version, $externalLink)) {
                    $this->redirect('/admin/dashboard');
                } else {
                    die("Error: Database insertion failed.");
                }
            } catch (\PDOException $e) {
                die("Database Error: " . $e->getMessage());
            }
        }
    }
    
    // --- Product Editing & Images ---
    
    public function editProduct() {
        $id = $_GET['id'] ?? null;
        $product = null;
        $images = [];
        
        if ($id) {
            $productModel = new Product();
            $product = $productModel->getById($id);
            $images = $productModel->getImages($id);
        }
        
        $this->view('admin/product_editor', ['product' => $product, 'images' => $images]);
    }
    
    public function updateProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $id = $_POST['id'] ?? null;
             
             // If no ID, redirect to add (or handle create here, but we have addProduct separate)
             // Ideally we should merge add/edit, but for now let's focus on Update.
             if (!$id) { die("Invalid ID for update"); }

             $title = $_POST['title'];
             $description = $_POST['description'];
             $price = $_POST['price'];
             $type = $_POST['type'];
             $version = $_POST['version'];
             $externalLink = $_POST['external_link'] ?? null;
             
             // Handle File Update (Optional)
             $filePath = null;
             if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
                $targetDir = dirname(__DIR__, 2) . "/public/uploads/products/";
                $fileName = basename($_FILES["file"]["name"]);
                $uniqueName = uniqid() . "_" . $fileName;
                move_uploaded_file($_FILES["file"]["tmp_name"], $targetDir . $uniqueName);
                $filePath = "uploads/products/" . $uniqueName;
             }

             $productModel = new Product();
             $productModel->update($id, $title, $description, $price, $type, $version, $filePath, $externalLink);
             
             // Handle Image Uploads
             if (isset($_FILES["images"])) {
                 $imageCount = count($_FILES["images"]["name"]);
                 $targetDir = dirname(__DIR__, 2) . "/public/uploads/images/";
                 if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                 
                 for ($i = 0; $i < $imageCount; $i++) {
                     if ($_FILES["images"]["error"][$i] == 0) {
                         $fileName = basename($_FILES["images"]["name"][$i]);
                         $uniqueName = uniqid() . "_img_" . $fileName;
                         if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $targetDir . $uniqueName)) {
                             $isPrimary = ($i == 0 && empty($productModel->getImages($id))) ? 1 : 0; // First image is primary if none exist
                             $productModel->addImage($id, "uploads/images/" . $uniqueName, $isPrimary);
                         }
                     }
                 }
             }

             $this->redirect('/admin/product/edit?id=' . $id . '&saved=true');
        }
    }
    
    public function deleteProductImage() {
        $id = $_GET['id'] ?? null;
        $productId = $_GET['product_id'] ?? null;
        if ($id && $productId) {
            $productModel = new Product();
            $productModel->deleteImage($id);
            $this->redirect('/admin/product/edit?id=' . $productId);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $productModel = new Product();
            $productModel->delete($id);
            $this->redirect('/admin/dashboard');
        }
    }

    public function logs() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        $logModel = new \App\Models\License(); 
        $logs = $logModel->getRecentLogs();
        
        $this->view('admin/logs', ['logs' => $logs]);
    }

    public function generateLicense() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $productId = $_POST['product_id'] ?? '';
            
            if ($email && $productId) {
                // Find User ID
                $db = new \App\Config\Database();
                $conn = $db->getConnection();
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();
                
                if ($user) {
                    $licenseModel = new \App\Models\License();
                    $key = $licenseModel->generate($user['id'], $productId);
                    if ($key) {
                         // Ideally set a flash message here
                    }
                } else {
                    die("User with email $email not found. Please ask them to register first.");
                }
            }
            $this->redirect('/admin/dashboard');
        }
    }

    public function settings() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
        
        $settingModel = new \App\Models\Setting();
        $settings = $settingModel->getAll();
        
        $this->view('admin/settings', ['settings' => $settings]);
    }

    public function saveSettings() {
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingModel = new \App\Models\Setting();
            
            $keys = [
                'site_name', 'stripe_publishable_key', 'stripe_secret_key', 'stripe_webhook_secret', 
                'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_from',
                'site_meta_keywords', 'site_description', 'google_analytics_id', 'google_search_console', 'custom_head_code', 'site_favicon'
            ];
            foreach ($keys as $key) {
                if (isset($_POST[$key])) {
                    $settingModel->set($key, $_POST[$key]);
                }
            }
            
            $this->redirect('/admin/settings?saved=true');
        }
    }
    // --- Page Management ---
    
    public function pages() {
        $pageModel = new \App\Models\Page();
        $pages = $pageModel->getAll();
        $this->view('admin/pages', ['pages' => $pages]);
    }
    
    public function editPage() {
        $pageModel = new \App\Models\Page();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $title = $_POST['title'];
            $slug = $_POST['slug'];
            $content = $_POST['content'];
            $metaTitle = $_POST['meta_title'];
            $metaDesc = $_POST['meta_description'];
            
            if ($id) {
                $pageModel->update($id, $title, $slug, $content, $metaTitle, $metaDesc);
            } else {
                $pageModel->create($title, $slug, $content, $metaTitle, $metaDesc);
            }
            $this->redirect('/admin/pages');
        } else {
            $id = $_GET['id'] ?? null;
            $page = null;
            if ($id) {
                $page = $pageModel->getById($id);
            }
            $this->view('admin/page_editor', ['page' => $page]);
        }
    }
    
    public function deletePage() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $pageModel = new \App\Models\Page();
            $pageModel->delete($id);
        }
        $this->redirect('/admin/pages');
    }
}
