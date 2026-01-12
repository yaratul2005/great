<?php

namespace App\Core;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        $viewPath = "../app/Views/" . $view . ".php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View does not exist: " . $view);
        }
    }

    public function redirect($path) {
        $url = url($path);
        header("Location: " . $url);
        exit();
    }
}
