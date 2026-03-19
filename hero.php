<?php
 $hero_args = array(
      'post_type'=> 'photos', // o cible le cpt
        'posts_per_page'=> 1, // on prends tous les posts presents dans le cpt ciblé
        'orderby'=> 'rand', // on met en mode random pour l'apparition
    );

  $datas_hero = new WP_Query($hero_args);

  if ($datas_hero->have_posts()) : // si au moins un poste existe on peut passer à la suite
    while ($datas_hero->have_posts()) : // on boucle tant qu'il y a un post
      $datas_hero->the_post(); // On recup le post courant
      $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large'); // on recup l'url de l'image du post courant avec ses dimenssions
    endwhile;
  endif;
?>
<div class="hero" style="background-image:url('<?php echo $image[0]; ?>')">
  <img src="http://maphoto.local/wp-content/uploads/2026/03/Titre-header.png" alt="">
</div>
</div>
