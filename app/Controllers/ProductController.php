<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class ProductController extends Controller {
    
    public function show($id) {
        $productModel = new Product();
        $product = $productModel->getById($id);
        
        if (!$product) {
            http_response_code(404);
            die("Product not found");
        }
        
        $images = $productModel->getImages($id);
        
        $this->view('products/show', ['product' => $product, 'images' => $images]);
    }
}
