<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Page;

class PageController extends Controller {
    
    public function show($slug) {
        $pageModel = new Page();
        $page = $pageModel->getBySlug($slug);
        
        if ($page) {
            $this->view('pages/show', ['page' => $page]);
        } else {
            // Check if 404
            http_response_code(404);
            echo "Page not found"; 
            // In a real app, render a 404 view
        }
    }
}
