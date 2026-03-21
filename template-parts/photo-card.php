<?php
$full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
$cats = get_the_terms(get_the_ID(), 'photo_categorie');
$cat_name = $cats ? $cats[0]->name : '';
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