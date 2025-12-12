<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fil d'actualité</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-3">
    <nav class="navbar navbar-dark bg-primary mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand">EntrepriseSocial</span>
            <div class="d-flex">
                <span class="text-white me-3 mt-2">Bonjour, <?php echo htmlspecialchars($_SESSION['user_nom']); ?></span>
                <a href="?action=logout" class="btn btn-outline-light">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Quoi de neuf ?</h5>
                <form action="?action=create_post" method="post">
                    <input type="text" name="titre" class="form-control mb-2" placeholder="Titre" required>
                    <textarea name="contenu" class="form-control mb-2" placeholder="Votre message..." required></textarea>
                    <button type="submit" class="btn btn-primary">Publier</button>
                </form>
            </div>
        </div>

        <?php foreach ($posts as $post): ?>
            <div class="card mb-3 shadow-sm post-card" data-post-id="<?php echo $post['id']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($post['titre']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">Par <?php echo htmlspecialchars($post['auteur_nom']); ?></h6>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($post['contenu'])); ?></p>
                    
                    <?php if ($post['utilisateur_id'] == $_SESSION['user_id']): ?>
                        <a href="?action=delete_post&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger mb-3">Supprimer</a>
                    <?php endif; ?>

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