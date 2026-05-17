document.addEventListener('DOMContentLoaded', () => {

    const dropdowns = document.querySelectorAll('.dropdown, .dropdown-right');
    const form = document.getElementById('filter-form');

    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-btn');
        const menu = dropdown.querySelector('.dropdown-menu');
        const input = dropdown.querySelector('input');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();

            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) m.classList.remove('open');
            });

            menu.classList.toggle('open');
        });

        menu.querySelectorAll('li').forEach(item => {
            item.addEventListener('click', () => {

                const value = item.dataset.value;

                input.value = value;
                btn.textContent = item.textContent + ' ▼';

                // AJAX  pour contourner le refresh simple
                fetchFilteredPhotos();

            });
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            m.classList.remove('open');
        });
    });

    // On crée notre fonction qui va utiliser AJAX
    function fetchFilteredPhotos() {

        const formData = new FormData(form);

        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'filter_photos',
                categorie: formData.get('categorie'),
                format: formData.get('format'),
                order: formData.get('order')
            })
        })
        .then(res => res.text())
        .then(html => {
            document.querySelector('.section-display-media').innerHTML = html;

            // reset bouton load more
            const btn = document.getElementById('load-more');
            if (btn) {
                btn.dataset.offset = 8;
                btn.style.display = 'block';
            }
        });
    }

});