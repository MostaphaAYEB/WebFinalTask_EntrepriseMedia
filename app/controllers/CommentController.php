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
            $input = json_decode(file_get_contents('php://input'), true);
    
            $contenu = $input['contenu'] ?? $_POST['contenu'] ?? '';
            $postId = $input['post_id'] ?? $_POST['post_id'] ?? null;
            $userId = $_SESSION['user']['id'];

            if ($contenu && $postId) {
                $newComment = $this->commentModel->addComment($contenu, $userId, $postId);
                header('Content-Type: application/json');
                echo json_encode($newComment);
                exit;
            }
        }
    }

    // --- NOUVELLE FONCTION ---
    public function update() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Non connecté']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if ($input && isset($input['comment_id']) && isset($input['contenu'])) {
            $success = $this->commentModel->updateComment($input['comment_id'], $input['contenu'], $_SESSION['user']['id']);
            
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Impossible de modifier (êtes-vous l\'auteur ?)']);
            }
        } else {
            echo json_encode(['error' => 'Données invalides']);
        }
        exit;
    }
}
