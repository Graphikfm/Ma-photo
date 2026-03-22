<?php get_header(); ?>

<main class="site-main">
  <div class="hero-image">
    <?php
    include 'hero.php';
     ?>
  </div>
</main>

<section class="section-media">

  <!-- FILTRES -->
  <section class="section-filter">

<form method="get" id="filter-form">

<div class="container-filtres">

    <!-- CATEGORIES -->
    <div class="dropdown">
        <button type="button" class="dropdown-btn">
            Catégories
            <div class="dropdown-arrow"></div>
        </button>

        <ul class="dropdown-menu">
          <li class="empty" data-value=""></li>
            <?php
            $categories = get_terms([
                'taxonomy' => 'photo_categorie',
                'hide_empty' => false
            ]);
             
            foreach ($categories as $cat) :
            ?>
                <li data-value="<?= esc_attr($cat->slug); ?>">
                    <?= esc_html($cat->name); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <input type="hidden" name="categorie" value="<?= $_GET['categorie'] ?? ''; ?>">
    </div>

    <!-- FORMATS -->
    <div class="dropdown">
        <button type="button" class="dropdown-btn">
            Formats
            <div class="dropdown-arrow"></div>
        </button>

        <ul class="dropdown-menu">
            <li class="empty" data-value=""></li>
            <?php
            $formats = get_terms([
                'taxonomy' => 'photo_format',
                'hide_empty' => false
            ]);
            foreach ($formats as $format) :
            ?>
                <li data-value="<?= esc_attr($format->slug); ?>">
                    <?= esc_html($format->name); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <input type="hidden" name="format" value="<?= $_GET['format'] ?? ''; ?>">
    </div>

</div>

<!-- TRI -->
<div class="dropdown-right">
    <button type="button" class="dropdown-btn">
        Trier par
        <div class="dropdown-arrow"></div>
    </button>

    <ul class="dropdown-menu">
        <li class="empty" data-value=""></li>
        <li data-value="DESC">Plus récentes</li>
        <li data-value="ASC">Plus anciennes</li>
    </ul>

    <input type="hidden" name="order" value="<?= $_GET['order'] ?? ''; ?>">
</div>

</form>

</section>

  <!-- AFFICHAGE PHOTOS -->
  <section class="section-display-media">

<?php

// premiere etape on initialise $args pour structurer la demande 
$args = [
  'post_type' => 'photos', // le nom du cpt
  'posts_per_page' => 8, // le nombre max de posts à afficher
  'tax_query' => [] // le system de filtre pour le moment initialisé à vide
];
// filtre cat que l'on remplis dans le tax_query de notre $arg
if (!empty($_GET['categorie'])) {
  $args['tax_query'][] = [
    'taxonomy' => 'photo_categorie',
    'field' => 'slug',
    'terms' => $_GET['categorie']
  ];
}
// filtre format pareil
if (!empty($_GET['format'])) {
  $args['tax_query'][] = [
    'taxonomy' => 'photo_format',
    'field' => 'slug',
    'terms' => $_GET['format']
  ];
}
 
if (count($args['tax_query']) > 1) {
  $args['tax_query']['relation'] = 'AND';
}

// trier par date pareil
if (!empty($_GET['order'])) {

  $args['orderby'] = 'date';
  $args['order'] = $_GET['order'];

}

$query = new WP_Query($args);  // deuxieme etape wp recupere les posts / photos
?>
<!-- Si il existe au moins un post on boucle tant qu'il y a un post à afficher (8 max) -->
<?php if ($query->have_posts()) : ?> 
<?php while ($query->have_posts()) : $query->the_post(); ?>

<?php
  // Image full
  $full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');

  // Catégorie
  $cats = get_the_terms(get_the_ID(), 'photo_categorie');
  $cat_name = $cats ? $cats[0]->name : '';

  // Référence (champ personnalisé natif)
  $reference = get_post_meta(get_the_ID(), 'reference', true);
?>
<?php get_template_part('template-parts/photo-card'); ?>

<?php endwhile; ?>
<?php else : ?>
  <p>Aucune photo trouvée.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

  </section>
<!--  On utilise le dataset pour stocker la valeur de l'offset cpt  -->
  <button id="load-more" data-offset="8"> <!-- On defini la valeur de l'offset apres le chargement de la page pour avoir un repere pour le load more -->

  Charger plus
</button>
</section>

<!-- LIGHTBOX -->
<div id="lightbox" class="hidden">
  <div class="lightbox-overlay"></div>

  <div class="lightbox-content">
    <span class="lightbox-close">×</span>
    <span class="lightbox-prev">← Précédente</span>
    <div class="image-wrapper">
 <img id="lightbox-img" src="" alt="">
 <div class="lightbox-meta"></div>
    </div>
   
    

    <span class="lightbox-next">Suivante →</span>
  </div>
</div>

<!-- SCRIPT -->
<script>
// let currentIndex = 0;

// document.addEventListener('click', function(e) {

//   if (e.target.classList.contains('open-lightbox')) {

//     e.preventDefault();

//     const allVignettes = document.querySelectorAll('.vignette');
//     const clickedVignette = e.target.closest('.vignette');

//     currentIndex = Array.from(allVignettes).indexOf(clickedVignette);

//     openLightbox();
//   }

// });

// function openLightbox() {

//   const allVignettes = document.querySelectorAll('.vignette');
//   const v = allVignettes[currentIndex];

//   document.getElementById('lightbox-img').src = v.dataset.full;

//   document.querySelector('.lightbox-meta').innerHTML =
//     `<span>${v.dataset.ref}</span><span>${v.dataset.cat}</span>`;

//   document.getElementById('lightbox').classList.remove('hidden');
// }

// document.querySelector('.lightbox-close').onclick = closeLightbox;
// document.querySelector('.lightbox-overlay').onclick = closeLightbox;

// function closeLightbox() {
//   document.getElementById('lightbox').classList.add('hidden');
// }

// document.querySelector('.lightbox-prev').addEventListener('click', () => {

//   const allVignettes = document.querySelectorAll('.vignette');

//   currentIndex = (currentIndex - 1 + allVignettes.length) % allVignettes.length;

//   openLightbox();
//   });
//   document.querySelector('.lightbox-next').addEventListener('click', () => {

//   const allVignettes = document.querySelectorAll('.vignette');

//   currentIndex = (currentIndex + 1) % allVignettes.length;

//   openLightbox();
// });



//ajax LOAD MORE AU CLICK
// document.addEventListener('DOMContentLoaded', () => {
// // on recup l'element html qui represente le bouton charger plus
// const loadMoreBtn = document.getElementById('load-more');
// // on l'exploite dans une condition que si il existe bien cet element alors on lui applique un evenement au click
// if (loadMoreBtn) {

//   loadMoreBtn.addEventListener('click', function() {
//   // on recup la valeur du dataset mis en place dans notre element HTML
//     const offset = parseInt(this.dataset.offset);
//      const form = document.getElementById('filter-form');
//     const formData = new FormData(form);
//     // on recup ajax dans wordpress à partir du chemin normalisé
//     // Structure de la requette HTTP -> url / mehtode / headers / le body (données à envoyer)
//     fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
//   method: 'POST',
//   headers: {
//     'Content-Type': 'application/x-www-form-urlencoded'
//   },
//   body: new URLSearchParams({
//     action: 'load_more_photos',
//     offset: offset,
//     categorie: formData.get('categorie') || '',
//     format: formData.get('format') || '',
//     order: formData.get('order') || ''
//   })
// })
//     .then(response => response.text()) // lecture text des données
//     .then(data => {
//       console.log(data.trim());
//       console.log(data);
//       // si data est vide (qu'il n'y a plus de posts a charger) le bt disparait
//       if (data.trim() === '') {
//         this.style.display = 'none';
//         return;
//       } else { //sinon on ajoute les données avant fin de la section html
//          document.querySelector('.section-display-media').insertAdjacentHTML('beforeend', data);
//       this.dataset.offset = offset + 4;
//       }
      
      

//     });

//   });

// }});

// LOAD MORE version au scroll
document.addEventListener('DOMContentLoaded', () => {

  let isLoading = false; // on place un controle de start stop du load des posts initialisé stop

  window.addEventListener('scroll', () => {

    const scrollTop = window.scrollY; // on recupere la valeur de la position du scroll in real time
    const windowHeight = window.innerHeight; // on recup la hauteur du viewport
    const documentHeight = document.body.offsetHeight; // on recup la hauteur de la page html

    if (scrollTop + windowHeight >= documentHeight - 100) { //si jusqu'ou je vois sur la page correspond à au moins la valeur de la hauteur de la page html -100 (histoire de declancher un peu avant la fin pour avoir de la marge)

      if (isLoading) return;
      isLoading = true; // On start

      const loadMoreBtn = document.getElementById('load-more'); // on recup l'element html qui possede le dataset de l'offset
      const offset = parseInt(loadMoreBtn.dataset.offset); // On stock la valeur de l'offset pour l'utiliser dans la requette ajax

      const form = document.getElementById('filter-form');
      const formData = new FormData(form);

      fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        // on structure ce que l'on va fetch -> Donne-moi les photos filtrées avec ces critères, à partir de telle position
        body: new URLSearchParams({
          action: 'load_more_photos', // on vient chercher la fonction 
          offset: offset, // on balance dans la fonction load_more_photos() la valeur de l'offset grace au dataset lié au bouton charger plus
          categorie: formData.get('categorie') || '',
          format: formData.get('format') || '',
          order: formData.get('order') || ''
        })
      })
      .then(response => response.text())
      .then(data => {

        if (data.trim() === '') {
          loadMoreBtn.style.display = 'none';
          return;
        }

        document.querySelector('.section-display-media')
          .insertAdjacentHTML('beforeend', data);

        loadMoreBtn.dataset.offset = offset + 4; // on declanche un affichage 4 par 4 à chaque fois que le scroll arrive au bas de la page html
        isLoading = false; // On stop le chargement et on attends de scroll de nouveau en bas
      });

    }

  });

});





document.addEventListener('DOMContentLoaded', () => {
  const initialTexts = new Map();

    const dropdowns = document.querySelectorAll('.dropdown, .dropdown-right');
    const form = document.getElementById('filter-form');

    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-btn');
        const menu = dropdown.querySelector('.dropdown-menu');
        const input = dropdown.querySelector('input');
         const allDropDownArrows = document.querySelectorAll('.dropdown-arrow');
         initialTexts.set(dropdown, btn.childNodes[0].nodeValue.trim());

        btn.addEventListener('click', (e) => {
            e.stopPropagation(); // on maintient ouvert le dropdown

            // On récupère tous les dropdowns du document
const allDropDowns = document.querySelectorAll('.dropdown-menu');

// On parcourt chaque dropdown un par un
allDropDowns.forEach(function(menuItem) {

    // On vérifie si le dropdown actuel est différent
    // de celui sur lequel on vient de cliquer
    const isDifferentMenu = (menuItem !== menu);

    // Si c'est un autre menu
    if (isDifferentMenu) {

        // On enlève la classe "open"
        // ce qui ferme le dropdown
        menuItem.classList.remove('open');
    }

});
            menu.classList.toggle('open');
            console.log(menu);
            const button = menu.parentElement;
            const arrowButton = button.querySelector('.dropdown-arrow');
            arrowButton.classList.toggle('up-arrow');
            console.log(btn.childNodes[0]);
            
        });

        menu.querySelectorAll('li').forEach(item => {

    item.addEventListener('click', () => {

        const value = item.dataset.value;

        input.value = value;


        if (value !== '') {

            // on affiche la valeur choisie
            btn.childNodes[0].nodeValue = value;

        } else {

            // RESET vers le texte d'origine
            btn.childNodes[0].nodeValue = initialTexts.get(dropdown);

            //  aussi l'input
            input.value = '';
        }

        // fermeture du menu (optionnel mais propre)
        menu.classList.remove('open');

        // appel AJAX
        fetchFilteredPhotos();
    });

});
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => {
            m.classList.remove('open');
        });
    });

    // On crée notre fonction qui va utiliser AJAX si on filtre avec les dropdowns
    function fetchFilteredPhotos() {

        const formData = new FormData(form); // on vient chercher le formulaire qui englobe les dropdowns

        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'filter_photos',
                categorie: formData.get('categorie'), // on vient get la valeur lié au dropdown categorie (on recup la valeur selectionnée dans ce dropdown) qu'on envoi au php
                format: formData.get('format'), // on vient get la valeur lié au dropdown format (on recup la valeur selectionnée dans ce dropdown) qu'on envoi au php
                order: formData.get('order') // on vient get la valeur lié au dropdown order (on recup la valeur selectionnée dans ce dropdown) qu'on envoi au php
            })
        })
        .then(res => res.text())
        .then(html => {
            document.querySelector('.section-display-media').innerHTML = html;
          // console.log(html);
            // reset bouton load more
            const btn = document.getElementById('load-more');
            if (btn) {
                btn.dataset.offset = 8;
                btn.style.display = 'block';
            }
        })
    }

});
</script>

<?php get_footer(); ?>
