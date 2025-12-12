<?php
namespace App\Models;

use PDO;

class Notification {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::get();
    }

    public function create($userId, $message) {
        $stmt = $this->pdo->prepare("INSERT INTO notifications (utilisateur_id, message) VALUES (:uid, :msg)");
        return $stmt->execute(['uid' => $userId, 'msg' => $message]);
    }

    public function getUnread($userId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM notifications WHERE utilisateur_id = :uid AND lu = 0");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetch()['count'];
    }

    public function markAsRead($userId) {
        $stmt = $this->pdo->prepare("UPDATE notifications SET lu = 1 WHERE utilisateur_id = :uid");
        return $stmt->execute(['uid' => $userId]);
    }
}