<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\PostController;
use App\Controllers\CommentController;

$userController = new UserController();
$postController = new PostController();
$commentController = new CommentController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'login':
        $userController->login();
        break;
    case 'register':
        $userController->register();
        break;
    case 'logout':
        $userController->logout();
        break;
        
    case 'index':
        $postController->index();
        break;
    case 'create_post':
        $postController->create();
        break;
    case 'delete_post':
        $postController->delete();
        break;

    case 'ajax_comment':
        $commentController->add();
        break;

    default:
        if (isset($_SESSION['user_id'])) {
            header('Location: ?action=index');
        } else {
            header('Location: ?action=login');
        }
        break;
}