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

    public function setResetToken($email, $token) {
        // Set expiry to 1 hour from now
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':token' => $token, ':expires' => $expires, ':email' => $email]);
        
        return $stmt->rowCount() > 0;
    }

    public function validateResetToken($token) {
        $sql = "SELECT id FROM users WHERE reset_token = :token AND reset_expires > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch();
    }

    public function resetPassword($token, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $sql = "UPDATE users SET password_hash = :hash, reset_token = NULL, reset_expires = NULL WHERE reset_token = :token AND reset_expires > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':hash' => $passwordHash, ':token' => $token]);
        
        return $stmt->rowCount() > 0;
    }

    public function updatePassword($userId, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password_hash = :hash WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':hash' => $passwordHash, ':id' => $userId]);
    }

    public function updateProfile($userId, $name, $email) {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':name' => $name, ':email' => $email, ':id' => $userId]);
    }
}
