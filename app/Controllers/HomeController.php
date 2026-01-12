<?php

namespace App\Controllers;

use App\Core\Controller;

use App\Models\Product;

class HomeController extends Controller {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $productModel = new Product();
        $products = $productModel->getAll();
        
        $data = [
            'user' => $_SESSION['user_name'] ?? null,
            'products' => $products
        ];
        $this->view('home/index', $data);
    }
}
