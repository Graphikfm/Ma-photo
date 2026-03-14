<?php if ( ! defined('ABSPATH') ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="site-header">
  <div class="container">
    <p class="site-description"><?php bloginfo('description'); ?></p>

    <nav class="main-nav" aria-label="Menu principal">
      <img class="burger-btn" src="http://maphoto.local/wp-content/uploads/2026/03/Statedefault.png" alt="">
      <?php
      wp_nav_menu([
        'theme_location' => 'Header',
        'container'      => false,
        'menu_class'     => 'menu',
        'fallback_cb'    => '__return_empty_string',
      ]);
      ?>
          <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <img src="http://maphoto.local/wp-content/uploads/2026/01/Nathalie-Mota.png" alt="">
    </a>
      <ul>
        <li>
            <a href="index.php">ACCUEIL </a>
        </li>
        <li>
           <a href="#"> A PROPOS</a>
        </li>
        <li>
            <div class="contact-button open-contact">
              CONTACT
            </div>
        </li>
      </ul>
    </nav>
    
    <div class="mobile-header-container">
      <img src="http://maphoto.local/wp-content/uploads/2026/01/Nathalie-Mota.png" alt="">
      <img class="burger-btn" src="http://maphoto.local/wp-content/uploads/2026/03/Statedefault.png" alt="">
      
    </div>
    <nav class="little-nav">
      
      <ul>
        <li>
            <a href="index.php">ACCUEIL </a>
        </li>
        <li>
           <a href="#"> A PROPOS</a>
        </li>
        <li>
            <div class="contact-button open-contact">
              CONTACT
            </div>
        </li>
      </ul>
    </nav>

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

</header>
