<?php
namespace App\Models;

use PDO;

class Post {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::get();
    }

    public function getAllPosts() {
        $sql = "SELECT posts.*, users.nom as auteur_nom 
                FROM posts 
                JOIN users ON posts.utilisateur_id = users.id 
                ORDER BY date_publication DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createPost($titre, $contenu, $userId) {
        $stmt = $this->pdo->prepare("INSERT INTO posts (titre, contenu, utilisateur_id) VALUES (:titre, :contenu, :uid)");
        return $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'uid' => $userId]);
    }

    public function deletePost($id, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id AND utilisateur_id = :uid");
        return $stmt->execute(['id' => $id, 'uid' => $userId]);
    }
}