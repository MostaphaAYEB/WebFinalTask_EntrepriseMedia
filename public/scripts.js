document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.form-comment-ajax');

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const card = form.closest('.post-card');
            const postId = card.dataset.postId;
            const input = form.querySelector('.comment-input');
            const content = input.value;
            const list = card.querySelector('.comments-list');

            fetch('?action=ajax_comment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ post_id: postId, contenu: content })
            })
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                } else {
                    const div = document.createElement('div');
                    div.className = 'alert alert-secondary p-2 mb-1';
                    div.innerHTML = `<strong>${data.auteur_nom}:</strong> ${data.contenu}`;
                    list.appendChild(div);
                    input.value = '';
                }
            });
        });
    });
});