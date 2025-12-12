<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fil d'actualité</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light p-3" id="main-body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 rounded sticky-top">
        <div class="container-fluid">
            <form class="d-flex w-50 position-relative me-3">
                <input class="form-control me-2" type="search" id="live-search" placeholder="Rechercher un post ou un utilisateur..." aria-label="Search">
                <div id="search-results" class="list-group position-absolute w-100" style="top: 40px; z-index: 1000; display: none;"></div>
            </form>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-light btn-sm" id="dark-mode-toggle">🌙</button>

                <button class="btn btn-primary position-relative">
                    🔔
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notif-badge">
                        0
                    </span>
                </button>

                <span class="text-white ms-2">Bonjour, <?php echo htmlspecialchars($_SESSION['user']['nom'] ?? 'Utilisateur'); ?></span>
                <a href="?page=logout" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Quoi de neuf ?</h5>
                <form action="?page=create_post" method="post">
                    <input type="text" name="titre" class="form-control mb-2" placeholder="Titre" required>
                    <textarea name="contenu" class="form-control mb-2" placeholder="Votre message..." required></textarea>
                    <button type="submit" class="btn btn-primary">Publier</button>
                </form>
            </div>
        </div>

        <?php foreach ($posts as $post): ?>
            <div class="card mb-3 shadow-sm post-card" data-post-id="<?php echo $post['id']; ?>" id="post-<?php echo $post['id']; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($post['titre']); ?></h5>
                        <small class="text-muted">
                            <?php echo date('d/m/Y à H:i', strtotime($post['date_publication'])); ?>
                        </small>
                    </div>

                    <h6 class="card-subtitle mt-1 mb-2 text-muted">Par <?php echo htmlspecialchars($post['auteur_nom']); ?></h6>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($post['contenu'])); ?></p>
                    
                    <div class="d-flex gap-2 mb-3">
                        <?php 
                            $likedClass = ($post['a_like'] > 0) ? 'btn-danger' : 'btn-outline-danger';
                            $heartIcon = ($post['a_like'] > 0) ? 'bi-heart-fill' : 'bi-heart';
                        ?>
                        <button class="btn btn-sm <?php echo $likedClass; ?> btn-like">
                            <i class="bi <?php echo $heartIcon; ?>"></i> 
                            <span class="like-count"><?php echo $post['nb_likes']; ?></span> J'aime
                        </button>

                        <?php 
                        $currentUserId = $_SESSION['user']['id'] ?? 0;
                        if ($post['utilisateur_id'] == $currentUserId): 
                        ?>
                            <a href="?page=delete_post&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-secondary">Supprimer</a>
                        <?php endif; ?>
                    </div>

                    <hr>
                    <h6>Commentaires</h6>
                    <div class="comments-list">
                        <?php 
                        if (!empty($post['comments'])) {
                            foreach ($post['comments'] as $comment) {
                                include __DIR__ . '/../comment/comment.php';
                            }
                        }
                        ?>
                    </div>

                    <form class="form-comment-ajax mt-2">
                        <div class="input-group">
                            <input type="text" class="form-control comment-input" placeholder="Votre commentaire..." required>
                            <button class="btn btn-outline-primary" type="submit">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script src="scripts.js"></script>
</body>
</html>