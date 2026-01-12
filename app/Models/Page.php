<?php

namespace App\Models;

use App\Core\Model;

class Page extends Model {
    
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM pages ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function getBySlug($slug) {
        $stmt = $this->conn->prepare("SELECT * FROM pages WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }
    
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pages WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($title, $slug, $content, $metaTitle, $metaDesc) {
        $sql = "INSERT INTO pages (title, slug, content, meta_title, meta_description) VALUES (:title, :slug, :content, :m_title, :m_desc)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':content' => $content,
            ':m_title' => $metaTitle,
            ':m_desc' => $metaDesc
        ]);
    }
    
    public function update($id, $title, $slug, $content, $metaTitle, $metaDesc) {
        $sql = "UPDATE pages SET title = :title, slug = :slug, content = :content, meta_title = :m_title, meta_description = :m_desc WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':slug' => $slug,
            ':content' => $content,
            ':m_title' => $metaTitle,
            ':m_desc' => $metaDesc
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM pages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
