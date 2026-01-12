<?php

namespace App\Models;

use App\Core\Model;

class Order extends Model {
    public function create($userId, $productId, $amount, $currency = 'USD') {
        $sql = "INSERT INTO orders (user_id, product_id, amount, currency, status) VALUES (:uid, :pid, :amount, :curr, 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':uid' => $userId,
            ':pid' => $productId,
            ':amount' => $amount,
            ':curr' => $currency
        ]);
        return $this->conn->lastInsertId();
    }

    public function markAsPaid($orderId, $txId) {
        $sql = "UPDATE orders SET status = 'paid', transaction_id = :txid WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':txid' => $txId, ':id' => $orderId]);
    }
    
    public function getById($id) {
        $sql = "SELECT o.*, p.title as product_title, p.description as product_desc, u.name as user_name, u.email as user_email 
                FROM orders o 
                JOIN products p ON o.product_id = p.id 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getByUser($userId) {
        $sql = "SELECT o.*, p.title as product_title FROM orders o 
                JOIN products p ON o.product_id = p.id 
                WHERE o.user_id = :uid AND o.status = 'paid' 
                ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }
}
