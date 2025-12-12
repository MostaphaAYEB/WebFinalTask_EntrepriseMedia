<?php
namespace App\Models;

use PDO;

class Comment {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::get();
    }

    public function getCommentsByPost($postId) {
        $sql = "SELECT comments.*, users.nom as auteur_nom 
                FROM comments 
                JOIN users ON comments.utilisateur_id = users.id 
                WHERE post_id = :pid 
                ORDER BY date_commentaire ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pid' => $postId]);
        return $stmt->fetchAll();
    }

    public function addComment($contenu, $userId, $postId) {
        $stmt = $this->pdo->prepare("INSERT INTO comments (contenu, utilisateur_id, post_id) VALUES (:contenu, :uid, :pid)");
        $stmt->execute(['contenu' => $contenu, 'uid' => $userId, 'pid' => $postId]);
        
        $id = $this->pdo->lastInsertId();
        // Récupération pour AJAX
        $sql = "SELECT comments.*, users.nom as auteur_nom FROM comments JOIN users ON comments.utilisateur_id = users.id WHERE comments.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}