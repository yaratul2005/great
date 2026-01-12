<?php

namespace App\Models;

use App\Core\Model;

class License extends Model {
    public function generate($userId, $productId, $domain = null) {
        $key = strtoupper(substr(md5(uniqid(rand(), true)), 0, 16));
        // Format as XXXX-XXXX-XXXX-XXXX
        $formattedKey = implode('-', str_split($key, 4));
        
        $sql = "INSERT INTO licenses (user_id, product_id, license_key, domain_restriction) VALUES (:user_id, :product_id, :license_key, :domain)";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':license_key' => $formattedKey,
            ':domain' => $domain
        ])) {
            return $formattedKey;
        }
        
        return false;
    }

    public function validate($key, $productSecret, $domain = null) {
        // Validation now requires joining with Product table to check secret
        // Also Join Users to get email
        $sql = "SELECT l.*, p.api_secret, u.email, u.name as user_name 
                FROM licenses l 
                JOIN products p ON l.product_id = p.id 
                JOIN users u ON l.user_id = u.id
                WHERE l.license_key = :key";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':key' => $key]);
        $license = $stmt->fetch();
        
        $logStatus = 'failed';
        $logMessage = 'Invalid License Key';
        $licenseId = null;

        $result = ['valid' => false, 'message' => 'Invalid License Key'];
        
        if ($license) {
            $licenseId = $license['id'];
            
            // 1. Check Secret
            if ($license['api_secret'] !== $productSecret) {
                $logMessage = 'Invalid Product Secret';
                $result = ['valid' => false, 'message' => $logMessage];
            }
            // 2. Check Status
            elseif ($license['status'] !== 'active') {
                 $logMessage = 'License is ' . $license['status'];
                 $result = ['valid' => false, 'message' => $logMessage];
            }
            // 3. Check Domain (Optional Strict Mode)
            elseif (!empty($license['domain_restriction']) && $domain && strpos($domain, $license['domain_restriction']) === false) {
                 $logMessage = 'Invalid domain: ' . $domain;
                 $result = ['valid' => false, 'message' => 'Invalid domain'];
            }
            else {
                // Success
                $logStatus = 'success';
                $logMessage = 'License Verified';
                $result = [
                    'valid' => true, 
                    'message' => 'License Active',
                    'user' => [
                        'email' => $license['email'],
                        'name' => $license['user_name']
                    ]
                ];
            }
        }
        
        // Log the attempt
        $this->logAttempt($key, $logStatus, $logMessage, $licenseId, $domain);
        
        return $result;
    }

    private function logAttempt($key, $status, $message, $licenseId, $domain) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $sql = "INSERT INTO license_logs (license_key, ip_address, domain, status, message, user_agent, license_id) VALUES (:key, :ip, :domain, :status, :message, :ua, :lid)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':key' => $key,
            ':ip' => $ip,
            ':domain' => $domain,
            ':status' => $status,
            ':message' => $message,
            ':ua' => $userAgent,
            ':lid' => $licenseId
        ]);
    }

    public function getByUser($userId) {
        $sql = "SELECT l.*, p.title as product_title, p.version, p.file_path, p.external_link FROM licenses l JOIN products p ON l.product_id = p.id WHERE l.user_id = :user_id ORDER BY l.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getRecentLogs($limit = 50) {
        $sql = "SELECT logs.*, p.title as product_name, u.email as user_email 
                FROM license_logs logs
                LEFT JOIN licenses l ON logs.license_id = l.id
                LEFT JOIN products p ON l.product_id = p.id
                LEFT JOIN users u ON l.user_id = u.id
                ORDER BY logs.created_at DESC LIMIT " . (int)$limit;
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }
}
