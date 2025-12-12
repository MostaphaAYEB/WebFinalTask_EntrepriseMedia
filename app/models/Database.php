<?php
namespace App\Models;

use PDO;

class Database {
    private static ?PDO $pdo = null;

    public static function get(): PDO {
        if (self::$pdo === null) {
            $host = '127.0.0.1';
            $port = 8889; 
            $db   = 'reseau_social'; 
            $user = 'root';
            $pass = 'root'; 

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }
}
?>