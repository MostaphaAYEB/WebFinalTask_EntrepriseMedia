<div class="alert alert-secondary p-2 mb-1 comment-item" data-comment-id="<?php echo $comment['id']; ?>">
    <strong><?php echo htmlspecialchars($comment['auteur_nom']); ?>:</strong>
    
    <span class="comment-content" style="outline: none;"><?php echo htmlspecialchars($comment['contenu']); ?></span>

    <?php 
    $currentUserId = $_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? 0;
    if ($comment['utilisateur_id'] == $currentUserId): 
    ?>
        <small class="float-end">
            <a href="#" class="text-primary text-decoration-none btn-edit-comment">Modifier</a>
            <a href="#" class="text-success text-decoration-none btn-save-comment d-none">Enregistrer</a>
        </small>
    <?php endif; ?>
</div>