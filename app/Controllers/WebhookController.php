<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\License;

class WebhookController extends Controller {
    
    // Endpoint: /webhook/helio
    public function stripe() {
        // Retrieve the request's body
        $payload = @file_get_contents('php://input');
        $event = json_decode($payload, true);

        if (!$event || !isset($event['type'])) {
            http_response_code(400);
            exit();
        }

        // Handle the checkou.session.completed event
        if ($event['type'] == 'checkout.session.completed') {
            $session = $event['data']['object'];
            
            // SECURITY: Verify this session with Stripe API directly
            $settingModel = new \App\Models\Setting();
            $stripeSecret = $settingModel->get('stripe_secret_key');
            
            if (!$stripeSecret) {
                http_response_code(500);
                exit();
            }

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://api.stripe.com/v1/checkout/sessions/" . $session['id']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $stripeSecret
            ]);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            
            $verifiedSession = json_decode($response, true);
            
            if (isset($verifiedSession['payment_status']) && $verifiedSession['payment_status'] == 'paid') {
                $orderId = $verifiedSession['client_reference_id'];
                
                // Process Order
                $orderModel = new Order();
                $order = $orderModel->getById($orderId);
                
                if ($order && $order['status'] !== 'paid') {
                    // Update Order
                    $orderModel->markAsPaid($orderId, $verifiedSession['payment_intent'] ?? 'stripe_txn');
                    
                    // Generate License
                    $licenseModel = new License();
                    $licenseModel->generate($order['user_id'], $order['product_id']);
                }
            }
        }

        http_response_code(200);
    }
}
