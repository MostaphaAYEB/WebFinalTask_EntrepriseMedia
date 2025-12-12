<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;
use App\Controllers\PostController;
use App\Controllers\CommentController;

$userController = new UserController();
$postController = new PostController();
$commentController = new CommentController();

$page = $_GET['page'] ?? 'index';

switch ($page) {
    case 'login':
        $userController->login();
        break;
    case 'register':
        $userController->register();
        break;
    case 'logout':
        $userController->logout();
        break;
        
    // CORRECTION 2 : On ajoute 'home' car ton PostController redirige vers 'page=home'
    case 'home': 
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
        if (isset($_SESSION['user'])) {
            header('Location: ?page=index');
        } else {
            header('Location: ?page=login');
        }
        break;
}