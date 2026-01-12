<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Order;

class PaymentController extends Controller {
    
    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Enforce Login
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        
        $productId = $_GET['id'] ?? null;
        if (!$productId) {
            die("Product ID required");
        }
        
        $productModel = new Product();
        $product = $productModel->getById($productId);
        
        // Create Pending Order
        $orderModel = new Order();
        $orderId = $orderModel->create($_SESSION['user_id'], $productId, $product['price']);
        
        // Load Settings
        $settingModel = new \App\Models\Setting();
        $stripeSecret = $settingModel->get('stripe_secret_key');
        
        if ($stripeSecret) {
            $apiUrl = "https://api.stripe.com/v1/checkout/sessions"; 
            
            $data = http_build_query([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product['title'],
                            'description' => $product['description'],
                        ],
                        'unit_amount' => (int)($product['price'] * 100), // Cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => \App\Config\App::getBaseUrl() . "/dashboard?success=true&session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => \App\Config\App::getBaseUrl() . "/purchase?id=" . $productId,
                'client_reference_id' => $orderId,
                'metadata' => [
                    'order_id' => $orderId,
                    'product_id' => $productId
                ]
            ]);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $stripeSecret,
                'Content-Type: application/x-www-form-urlencoded'
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $resData = json_decode($response, true);
            
            if ($httpCode === 200 && isset($resData['url'])) {
                 header("Location: " . $resData['url']);
                 exit;
            } else {
                die("Stripe API Error: " . $response);
            }
            
        } else {
            // Fallback to Simulator if no settings
            $this->redirect("/payment/simulator?order_id=$orderId&amount=" . $product['price']);
        }
    }

    public function simulator() {
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $orderId = $_GET['order_id'] ?? 0;
        $amount = $_GET['amount'] ?? 0;
        
        $this->view('payment/simulator', ['orderId' => $orderId, 'amount' => $amount]);
    }
}
