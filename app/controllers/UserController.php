<?php
namespace App\Controllers;

use App\Models\User;

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            $password = $_POST['password'];

            if ($this->userModel->inscription($nom, $email, $password)) {
                header('Location: index.php?page=login');
                exit;
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
        require __DIR__ . '/../views/user/register.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email']);
            $password = $_POST['password'];

            $user = $this->userModel->connexion($email, $password);

            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: index.php?page=home');
                exit;
            } else {
                $error = "Identifiants incorrects.";
            }
        }
        require __DIR__ . '/../views/user/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
