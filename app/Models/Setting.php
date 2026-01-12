<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model {
    
    public function get($key) {
        $stmt = $this->conn->prepare("SELECT value FROM settings WHERE key_name = :key");
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch();
        return $result ? $result['value'] : null;
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM settings");
        $all = $stmt->fetchAll();
        $mapped = [];
        foreach ($all as $setting) {
            $mapped[$setting['key_name']] = $setting['value'];
        }
        return $mapped;
    }

    public function set($key, $value) {
        $sql = "INSERT INTO settings (key_name, value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE value = :value_update";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':key' => $key, 
            ':value' => $value,
            ':value_update' => $value
        ]);
    }
}
