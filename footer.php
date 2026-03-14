<?php if ( ! defined('ABSPATH') ) exit; ?>

<!-- modale -->
 

<?php get_template_part('template-parts/modal-contact'); ?>
<!-- end modale  -->

<footer class="site-footer">
  <div class="container">
    <nav class="footer-nav" aria-label="Menu pied de page">
      <?php
      wp_nav_menu([
        'theme_location' => 'footer',
        'container'      => false,
        'menu_class'     => 'menu-footer-1',
        'fallback_cb'    => '__return_empty_string',
      ]);
      ?>
    </nav>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>