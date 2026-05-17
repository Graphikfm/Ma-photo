let currentIndex = 0;

document.addEventListener('click', function(e) {

  if (e.target.classList.contains('open-lightbox')) {

    e.preventDefault();

    const allVignettes = document.querySelectorAll('.vignette');
    const clickedVignette = e.target.closest('.vignette');

    currentIndex = Array.from(allVignettes).indexOf(clickedVignette);

    openLightbox();
  }

});

function openLightbox() {

  const allVignettes = document.querySelectorAll('.vignette');
  const v = allVignettes[currentIndex];

  document.getElementById('lightbox-img').src = v.dataset.full;

  document.querySelector('.lightbox-meta').innerHTML =
    `<span>${v.dataset.ref}</span><span>${v.dataset.cat}</span>`;

  document.getElementById('lightbox').classList.remove('hidden');
}

document.querySelector('.lightbox-close').onclick = closeLightbox;
document.querySelector('.lightbox-overlay').onclick = closeLightbox;

function closeLightbox() {
  document.getElementById('lightbox').classList.add('hidden');
}

document.querySelector('.lightbox-prev').addEventListener('click', () => {

  const allVignettes = document.querySelectorAll('.vignette');

  currentIndex = (currentIndex - 1 + allVignettes.length) % allVignettes.length;

  openLightbox();
});

document.querySelector('.lightbox-next').addEventListener('click', () => {

  const allVignettes = document.querySelectorAll('.vignette');

  currentIndex = (currentIndex + 1) % allVignettes.length;

  openLightbox();
});


// AJAX FILTER
function fetchPhotos(reset = true) {

  const categorie = document.querySelector('[name="categorie"]').value;
  const format = document.querySelector('[name="format"]').value;
  const order = document.querySelector('[name="order"]').value;

  fetch(adminAjaxUrl, {

    method: 'POST',

    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },

    body: new URLSearchParams({
      action: 'filter_photos',
      categorie: categorie,
      format: format,
      order: order
    })

  })

  .then(response => response.text())

  .then(data => {

    document.querySelector('.section-display-media').innerHTML = data;

    document.getElementById('load-more').dataset.offset = 8;

  });

}


// DROPDOWN
document.querySelectorAll('.dropdown, .dropdown-right').forEach(dropdown => {

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

      btn.innerHTML = item.textContent + '<div class="dropdown-arrow"></div>';

      fetchPhotos();

    });

  });

});

document.addEventListener('click', () => {

  document.querySelectorAll('.dropdown-menu').forEach(m => {

    m.classList.remove('open');

  });

});


// /ajax

// on recup l'element html qui represente le bouton charger plus
const loadMoreBtn = document.getElementById('load-more');
// on l'exploite dans une condition que si il existe bien cet element alors on lui applique un evenement au click
if (loadMoreBtn) {

  loadMoreBtn.addEventListener('click', function() {
  // on recup la valeur du dataset mis en place dans notre element HTML
    const offset = parseInt(this.dataset.offset);

    const categorie = document.querySelector('[name="categorie"]')?.value || '';
    const format = document.querySelector('[name="format"]')?.value || '';
    const order = document.querySelector('[name="order"]')?.value || '';

    // on recup ajax dans wordpress à partir du chemin normalisé
    // Structure de la requette HTTP -> url / mehtode / headers / le body (données à envoyer)
    fetch(adminAjaxUrl, {
      method: 'POST',// On passe par la methode post pour afficher nos données
      // entete adapté pour wordpress
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      // les données à afficher seront de type string url action=load_more_photos&offset=8
      body: new URLSearchParams({
        action: 'load_more_photos', // On demande a wp d'executer l'action AJAX nommée load_more_photos -> $_POST['action'] = 'load_more_photos';
        offset: offset, // $offset = intval($_POST['offset']); 
        categorie: categorie,
        format: format,
        order: order
      })
    })
    .then(response => response.text()) // lecture text des données
    .then(data => {
      console.log(data.trim());
      // si data est vide (qu'il n'y a plus de posts a charger) le bt disparait
      if (data.trim() === '') {
        this.style.display = 'none';
        return;
      } else { //sinon on ajoute les données avant fin de la section html
         document.querySelector('.section-display-media').insertAdjacentHTML('beforeend', data);
      this.dataset.offset = offset + 8; // 8+8
      }
      
      

    });

  });

}