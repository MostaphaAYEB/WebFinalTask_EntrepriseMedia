<?php
namespace App\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;

class PostController
{
    private Post $postModel;
    private Comment $commentModel;
    private Notification $notifModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->commentModel = new Comment();
        $this->notifModel = new Notification();
    }

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $posts = $this->postModel->getAllPosts($userId);

        foreach ($posts as &$post) {
            $post['comments'] = $this->commentModel->getCommentsByPost($post['id']);
        }

        require __DIR__ . '/../views/post/index.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user'])) die("Accès interdit");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = htmlspecialchars($_POST['titre']);
            $contenu = htmlspecialchars($_POST['contenu']);
            $userId = $_SESSION['user']['id'];

            $this->postModel->createPost($titre, $contenu, $userId);
            header('Location: index.php?page=index');
            exit;
        }
    }

    public function delete()
    {
        if (isset($_GET['id']) && isset($_SESSION['user'])) {
            $this->postModel->deletePost($_GET['id'], $_SESSION['user']['id']);
        }
        header('Location: index.php?page=index');
        exit;
    }

    public function ajaxLike() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user'])) exit(json_encode(['error' => 'Non connecté']));

        $input = json_decode(file_get_contents('php://input'), true);
        if ($input && isset($input['post_id'])) {
            $result = $this->postModel->toggleLike($_SESSION['user']['id'], $input['post_id']);
            echo json_encode($result);
        }
        exit;
    }

    public function ajaxSearch() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $query = $input['query'] ?? '';
        
        $results = $this->postModel->search($query);
        echo json_encode($results);
        exit;
    }

    public function ajaxNotifications() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user'])) exit(json_encode(['count' => 0]));

        $count = $this->notifModel->getUnread($_SESSION['user']['id']);
        echo json_encode(['count' => $count]);
        exit;
    }
}