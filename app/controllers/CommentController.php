<?php
namespace App\Controllers;

use App\Models\Comment;

class CommentController
{
    private Comment $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function add()
    {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu = htmlspecialchars($_POST['contenu'] ?? '');
            $postId = $_POST['post_id'] ?? null;
            $userId = $_SESSION['user']['id'];

            if ($contenu && $postId) {
                $newComment = $this->commentModel->addComment($contenu, $userId, $postId);

                header('Content-Type: application/json');
                echo json_encode($newComment);
                exit;
            }
        }
    }
}
