<?php if ( ! defined('ABSPATH') ) exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body >

<header class="site-header">
  <div class="container">
    <p class="site-description"><?php bloginfo('description'); ?></p>

    <nav class="main-nav" aria-label="Menu principal">
      
          <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <img src="http://maphoto.local/wp-content/uploads/2026/01/Nathalie-Mota.png" alt="">
    </a>
    <?php
?>
  <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'menu',
        'fallback_cb'    => '__return_empty_string',
      ]);
      ?>
    </nav>
    
    <div class="mobile-header-container">
      <a href="http://maphoto.local">
        <img src="http://maphoto.local/wp-content/uploads/2026/01/Nathalie-Mota.png" alt="">
      </a>
      <div id="burger-btn">
        <!-- <img  src="http://maphoto.local/wp-content/uploads/2026/03/Statedefault.png" alt=""> -->
      </div>
    </div>
    <nav class="little-nav">
       <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'menu',
        'fallback_cb'    => '__return_empty_string',
      ]);
      ?> 
    </nav>
</header>


