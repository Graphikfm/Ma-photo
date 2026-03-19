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
            <a class="contact-button open-contact">
              CONTACT
            </a>
        </li>
      </ul>
    </nav>
    
    <div class="mobile-header-container">
      <img src="http://maphoto.local/wp-content/uploads/2026/01/Nathalie-Mota.png" alt="">
      <div id="burger-btn">
        <!-- <img  src="http://maphoto.local/wp-content/uploads/2026/03/Statedefault.png" alt=""> -->
      </div>
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
</header>


