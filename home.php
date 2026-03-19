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
    <form method="get" class="filter_left">
      <div class="container-filtres">
      <!-- CATEGORIES -->
      <select name="categorie" onchange="this.form.submit()">
        <option value="">Catégories</option>
        <?php
        $categories = get_terms([
          'taxonomy' => 'photo_categorie',
          'hide_empty' => false
        ]);
        foreach ($categories as $cat) :
        ?>
          <option value="<?= esc_attr($cat->slug); ?>" <?= selected($_GET['categorie'] ?? '', $cat->slug); ?>>
            <?= esc_html($cat->name); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <!-- FORMATS -->
      <select name="format" onchange="this.form.submit()">
        <option value="">Formats</option>
        <?php
        $formats = get_terms([
          'taxonomy' => 'photo_format',
          'hide_empty' => false
        ]);
        foreach ($formats as $format) :
        ?>
          <option value="<?= esc_attr($format->slug); ?>" <?= selected($_GET['format'] ?? '', $format->slug); ?>>
            <?= esc_html($format->name); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <!-- TRI DATE -->
      <select class="select-tri" name="order" onchange="this.form.submit()">
        <option value="">Trier par</option>

        <option value="DESC" <?= selected($_GET['order'] ?? '', 'DESC'); ?>>
          Plus récentes
        </option>

        <option value="ASC" <?= selected($_GET['order'] ?? '', 'ASC'); ?>>
          Plus anciennes
        </option>

      </select>
    </form>
  </section>

  <!-- AFFICHAGE PHOTOS -->
  <section class="section-display-media">

<?php
$args = [
  'post_type' => 'photos',
  'posts_per_page' => 8,
  'tax_query' => []
];
// filtre cat
if (!empty($_GET['categorie'])) {
  $args['tax_query'][] = [
    'taxonomy' => 'photo_categorie',
    'field' => 'slug',
    'terms' => $_GET['categorie']
  ];
}
// filtre format
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

// trier par date
if (!empty($_GET['order'])) {

  $args['orderby'] = 'date';
  $args['order'] = $_GET['order'];

}

$query = new WP_Query($args);
?>

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

<div class="vignette"
     data-full="<?= esc_url($full[0]); ?>"
     data-cat="<?= esc_attr($cat_name); ?>"
     data-ref="<?= esc_attr($reference); ?>">

  <?php the_post_thumbnail('medium'); ?>

  <div class="vignette-overlay">

    <a href="<?php the_permalink(); ?>" class="vignette-link">
      <img src="http://maphoto.local/wp-content/uploads/2025/12/Group.png" alt="">
    </a>

    <img class="open-lightbox"
         src="http://maphoto.local/wp-content/uploads/2026/01/Icon_fullscreen.png"
         alt="">
  </div>
</div>

<?php endwhile; ?>
<?php else : ?>
  <p>Aucune photo trouvée.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

  </section>
<!--  On utilise le dataset pour stocker la valeur de l'offset cpt  -->
  <button id="load-more" data-offset="8">
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



//ajax

// on recup l'element html qui represente le bouton charger plus
const loadMoreBtn = document.getElementById('load-more');
// on l'exploite dans une condition que si il existe bien cet element alors on lui applique un evenement au click
if (loadMoreBtn) {

  loadMoreBtn.addEventListener('click', function() {
  // on recup la valeur du dataset mis en place dans notre element HTML
    const offset = parseInt(this.dataset.offset);

    // on recup ajax dans wordpress à partir du chemin normalisé
    // Structure de la requette HTTP -> url / mehtode / headers / le body (données à envoyer)
    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
      method: 'POST',// On passe par la methode post pour afficher nos données
      // entete adapté pour wordpress
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      // les données à afficher seront de type string url action=load_more_photos&offset=8
      body: new URLSearchParams({
        action: 'load_more_photos', // On demande a wp d'executer l'action AJAX nommée load_more_photos -> $_POST['action'] = 'load_more_photos';
        offset: offset // $offset = intval($_POST['offset']); 
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
</script>

<?php get_footer(); ?>
