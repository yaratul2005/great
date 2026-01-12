<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model {
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($title, $description, $price, $type, $filePath, $version, $externalLink = null) {
        $secret = bin2hex(random_bytes(32)); 
        
        $sql = "INSERT INTO products (title, description, price, type, file_path, version, api_secret, external_link) VALUES (:title, :description, :price, :type, :file_path, :version, :api_secret, :external_link)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':type' => $type,
            ':file_path' => $filePath,
            ':version' => $version,
            ':api_secret' => $secret,
            ':external_link' => $externalLink
        ]);
    }
    
    // Updated to include optional file_path
    public function update($id, $title, $description, $price, $type, $version, $filePath = null, $externalLink = null) {
         $sql = "UPDATE products SET title = :title, description = :description, price = :price, type = :type, version = :version, external_link = :external_link";
         
         $params = [
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':type' => $type,
            ':version' => $version,
            ':id' => $id,
            ':external_link' => $externalLink
        ];

        if ($filePath) {
            $sql .= ", file_path = :file_path";
            $params[':file_path'] = $filePath;
        }

         $sql .= " WHERE id = :id";
         
         $stmt = $this->conn->prepare($sql);
         return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // --- Image Management ---

    public function addImage($productId, $imagePath, $isPrimary = 0) {
        $stmt = $this->conn->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (:pid, :path, :primary)");
        return $stmt->execute([':pid' => $productId, ':path' => $imagePath, ':primary' => $isPrimary]);
    }

    public function getImages($productId) {
        $stmt = $this->conn->prepare("SELECT * FROM product_images WHERE product_id = :pid ORDER BY is_primary DESC, created_at DESC");
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll();
    }

    public function deleteImage($id) {
        // Ideally fetch path first to unlink file, but for now just DB deletion
        $stmt = $this->conn->prepare("DELETE FROM product_images WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    public function getPrimaryImage($productId) {
        $stmt = $this->conn->prepare("SELECT image_path FROM product_images WHERE product_id = :pid AND is_primary = 1 LIMIT 1");
        $stmt->execute([':pid' => $productId]);
        $img = $stmt->fetch();
        return $img ? $img['image_path'] : null; // Fallback?
    }
}
