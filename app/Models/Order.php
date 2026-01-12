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
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
