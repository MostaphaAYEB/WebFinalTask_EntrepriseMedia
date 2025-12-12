<?php
namespace App\Models;

use PDO;

class Post {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::get();
    }

    public function getAllPosts($currentUserId) {
        $sql = "SELECT posts.*, users.nom as auteur_nom,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as nb_likes,
                (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id AND likes.utilisateur_id = :uid) as a_like
                FROM posts 
                JOIN users ON posts.utilisateur_id = users.id 
                ORDER BY date_publication DESC, posts.id DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['uid' => $currentUserId]);
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

    public function toggleLike($userId, $postId) {
        $stmt = $this->pdo->prepare("SELECT id FROM likes WHERE utilisateur_id = :uid AND post_id = :pid");
        $stmt->execute(['uid' => $userId, 'pid' => $postId]);
        $like = $stmt->fetch();

        if ($like) {
            $del = $this->pdo->prepare("DELETE FROM likes WHERE id = :id");
            $del->execute(['id' => $like['id']]);
            $action = 'unliked';
        } else {
            $ins = $this->pdo->prepare("INSERT INTO likes (utilisateur_id, post_id) VALUES (:uid, :pid)");
            $ins->execute(['uid' => $userId, 'pid' => $postId]);
            $action = 'liked';
        }

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM likes WHERE post_id = :pid");
        $countStmt->execute(['pid' => $postId]);
        $total = $countStmt->fetch()['total'];

        return ['action' => $action, 'nb_likes' => $total];
    }

    public function search($query) {
        $query = "%$query%";
        $sql = "SELECT posts.*, users.nom as auteur_nom 
                FROM posts 
                JOIN users ON posts.utilisateur_id = users.id 
                WHERE posts.titre LIKE :q OR posts.contenu LIKE :q OR users.nom LIKE :q
                ORDER BY date_publication DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['q' => $query]);
        return $stmt->fetchAll();
    }
}