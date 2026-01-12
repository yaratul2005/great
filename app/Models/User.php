<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model {
    public function register($name, $email, $password, $token) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        // is_active = 0 by default now
        $sql = "INSERT INTO users (name, email, password_hash, is_active, activation_token) VALUES (:name, :email, :password_hash, 0, :token)";
        $stmt = $this->conn->prepare($sql);
        
        try {
            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password_hash' => $passwordHash,
                ':token' => $token
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Check Activation
            if ($user['is_active'] == 0) {
                return 'not_active';
            }
            return $user;
        }
        
        return false;
    }
    
    public function activate($token) {
        $sql = "UPDATE users SET is_active = 1, activation_token = NULL WHERE activation_token = :token";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->rowCount() > 0;
    }

    public function getUserById($id) {
        $sql = "SELECT id, name, email, role FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
