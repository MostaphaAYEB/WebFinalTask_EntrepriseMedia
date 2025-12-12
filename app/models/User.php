<?php
namespace App\Models;

use PDO;

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::get();
    }

    public function inscription($nom, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email, password) VALUES (:nom, :email, :pass)");
        return $stmt->execute(['nom' => $nom, 'email' => $email, 'pass' => $hash]);
    }

    public function connexion($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}