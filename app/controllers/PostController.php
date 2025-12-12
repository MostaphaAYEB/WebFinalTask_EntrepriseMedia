<?php
namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;

class PostController
{
    private Post $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $posts = $this->postModel->getAllPosts();

        $commentModel = new Comment();
        foreach ($posts as &$post) {
            $post['comments'] = $commentModel->getCommentsByPost($post['id']);
        }

        require __DIR__ . '/../views/post/index.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = htmlspecialchars($_POST['titre']);
            $contenu = htmlspecialchars($_POST['contenu']);
            $userId = $_SESSION['user']['id'];

            if ($this->postModel->createPost($titre, $contenu, $userId)) {
                header('Location: index.php?page=home');
                exit;
            } else {
                $error = "Erreur lors de la création du message.";
            }
        }
    }

    public function delete()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (isset($_GET['id'])) {
            $postId = $_GET['id'];
            $userId = $_SESSION['user']['id'];

            $this->postModel->deletePost($postId, $userId);
        }
        header('Location: index.php?page=home');
        exit;
    }
}
