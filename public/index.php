<?php

require_once '../app/Config/Database.php';
require_once '../app/Config/App.php';
require_once '../app/Helpers.php';
require_once '../app/Core/Controller.php';
require_once '../app/Core/Model.php';
require_once '../app/Core/Router.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoloader (Optional implementation if classes grow)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

$router = new Router();

// Define Routes
$router->add('GET', '', 'HomeController', 'index');
$router->add('GET', 'register', 'AuthController', 'register');
$router->add('POST', 'register', 'AuthController', 'register');
$router->add('GET', 'login', 'AuthController', 'login');
$router->add('POST', 'login', 'AuthController', 'login');
$router->add('GET', 'logout', 'AuthController', 'logout');
$router->add('GET', 'activate', 'AuthController', 'activate');
$router->add('GET', 'forgot-password', 'AuthController', 'forgotPassword');
$router->add('POST', 'forgot-password', 'AuthController', 'forgotPassword');
$router->add('GET', 'reset-password', 'AuthController', 'resetPassword');
$router->add('POST', 'reset-password', 'AuthController', 'resetPassword');

// Admin Routes
$router->add('GET', '/admin/dashboard', 'AdminController', 'dashboard');
$router->add('POST', '/admin/product/add', 'AdminController', 'addProduct');
$router->add('GET', '/admin/product/delete', 'AdminController', 'delete');
$router->add('POST', '/admin/license/generate', 'AdminController', 'generateLicense');
$router->add('GET', '/admin/logs', 'AdminController', 'logs');
$router->add('GET', '/admin/settings', 'AdminController', 'settings');
$router->add('POST', '/admin/settings/save', 'AdminController', 'saveSettings');
$router->add('GET', '/admin/product/edit', 'AdminController', 'editProduct');
$router->add('POST', '/admin/product/update', 'AdminController', 'updateProduct');
$router->add('GET', '/admin/product/delete-image', 'AdminController', 'deleteProductImage');

// API Routes
$router->add('GET', '/api/validate', 'ApiController', 'validate');

// Payment Routes
$router->add('GET', '/purchase', 'PaymentController', 'checkout'); // Was simulating before, now real logic
$router->add('GET', '/payment/simulator', 'PaymentController', 'simulator');
$router->add('POST', '/webhook/stripe', 'WebhookController', 'stripe');

// User Routes
$router->add('GET', 'dashboard', 'UserController', 'dashboard');
$router->add('GET', 'dashboard', 'UserController', 'dashboard');
$router->add('GET', 'purchase', 'UserController', 'purchase');
$router->add('GET', 'profile', 'UserController', 'profile');
$router->add('GET', 'profile/update', 'UserController', 'updateProfile'); // Fallback logic often handles POST via same URL but safer to split or ensure Router handles it
$router->add('POST', 'profile/update', 'UserController', 'updateProfile');
$router->add('POST', 'profile/password', 'UserController', 'updatePassword');
$router->add('GET', 'invoices', 'UserController', 'invoices');
$router->add('GET', 'invoice/print', 'UserController', 'downloadInvoice');

// Admin Pages
$router->add('GET', '/admin/pages', 'AdminController', 'pages');
$router->add('GET', '/admin/pages/edit', 'AdminController', 'editPage');
$router->add('POST', '/admin/pages/edit', 'AdminController', 'editPage');
$router->add('GET', '/admin/pages/delete', 'AdminController', 'deletePage');

// Dynamic Pages (Catch-all or manual routes)
// Note: Router is simple regex. We'll add a specific route for pages or let the router fail into 404,
// but actually we want something like /about-us. 
// For this simple router, we can check if the path exists as a page if no other route matches, 
// OR we just register a generic route pattern if supported. 
// Our Router.php supports regex keys. Let's add a generic one at the end.
// Assuming slugs are simple: [a-z0-9-]+
$router->add('GET', 'product/([0-9]+)', 'ProductController', 'show');
$router->add('GET', '([a-z0-9-]+)', 'PageController', 'show');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
