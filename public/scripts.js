document.addEventListener('DOMContentLoaded', () => {

    document.body.addEventListener('click', (e) => {
        const btnLike = e.target.closest('.btn-like');
        if (btnLike) {
            e.preventDefault();
            const card = btnLike.closest('.post-card');
            const postId = card.dataset.postId;
            const countSpan = btnLike.querySelector('.like-count');
            const icon = btnLike.querySelector('i');

            fetch('?page=ajax_like', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ post_id: postId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    countSpan.innerText = data.nb_likes;
                    if (data.action === 'liked') {
                        btnLike.classList.remove('btn-outline-danger');
                        btnLike.classList.add('btn-danger');
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                    } else {
                        btnLike.classList.remove('btn-danger');
                        btnLike.classList.add('btn-outline-danger');
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                    }
                }
            })
            .catch(err => console.error(err));
        }
    });

    const darkModeBtn = document.getElementById('dark-mode-toggle');
    const body = document.body;
    const navbar = document.querySelector('.navbar');

    if (darkModeBtn) {

        if(localStorage.getItem('theme') === 'dark') {
            enableDarkMode();
        }

        darkModeBtn.addEventListener('click', () => {
            if(body.classList.contains('dark-mode')) {
                disableDarkMode();
            } else {
                enableDarkMode();
            }
        });
    }

    function enableDarkMode() {
        body.classList.add('dark-mode');
        body.classList.remove('bg-light');
        if(navbar) {
            navbar.classList.remove('navbar-light', 'bg-primary');
            navbar.classList.add('navbar-dark', 'bg-dark');
        }
        localStorage.setItem('theme', 'dark');
        if(darkModeBtn) darkModeBtn.innerText = '☀️';
    }

    function disableDarkMode() {
        body.classList.remove('dark-mode');
        body.classList.add('bg-light');
        if(navbar) {
            navbar.classList.add('navbar-light', 'bg-primary');
            navbar.classList.remove('navbar-dark', 'bg-dark');
        }
        localStorage.setItem('theme', 'light');
        if(darkModeBtn) darkModeBtn.innerText = '🌙';
    }

    const searchInput = document.getElementById('live-search');
    const searchResults = document.getElementById('search-results');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value;
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            fetch('?page=ajax_search', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ query: query })
            })
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';
                if (data.length > 0) {
                    searchResults.style.display = 'block';
                    data.forEach(post => {
                        const item = document.createElement('a');
                        item.href = `#post-${post.id}`;
                        item.className = 'list-group-item list-group-item-action';
                        item.innerHTML = `<strong>${post.titre}</strong> <small class="text-muted">par ${post.auteur_nom}</small>`;
                        item.addEventListener('click', () => {
                            searchResults.style.display = 'none';
                            searchInput.value = '';
                        });
                        searchResults.appendChild(item);
                    });
                } else {
                    searchResults.style.display = 'none';
                }
            });
        });
    }
});