<?php
// ce bout de code est la structure dynamique d'une vignette que l'on bouclera dans home et dans single
$full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); // on recup les données de l'image à la une 
$cats = get_the_terms(get_the_ID(), 'photo_categorie'); // on recup le tablau d'objet lié à la catégorie
$cat_name = $cats ? $cats[0]->name : ''; // on recup l'index qui cible la valeur du nom de cette catégorie courante (on reste sur une categorie dans le contexte du single-photos.php)
$reference = get_post_meta(get_the_ID(), 'reference', true); // on vient chercher dans meta la réference liée à l'id du post
$upload_dir = wp_upload_dir(); // recup un tableau ou l'url allant jusqu'a uploads est recuperable avec 'baseurl'
?>

<!-- On construis notre html en se basant sur les dataset.  on lie les valeur pour chaque dataset  -->
<div class="vignette"
     data-full="<?= esc_url($full[0]); ?>"
     data-cat="<?= esc_attr($cat_name); ?>"
     data-ref="<?= esc_attr($reference); ?>">
<!-- wordpress recup l'image à la une et l'affiche en html en creant une img   -->
  <?php the_post_thumbnail('medium'); ?>

  <div class="vignette-overlay">
    <a href="<?php the_permalink(); ?>" class="vignette-link">
      <img src="<?= esc_url($upload_dir['baseurl'] . '/2025/12/Group.png'); ?>" alt="">
    </a>

    <img class="open-lightbox"
     src="<?= esc_url($upload_dir['baseurl'] . '/2026/01/Icon_fullscreen.png'); ?>"
     alt="">
  </div>
</div>