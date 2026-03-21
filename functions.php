<?php
function maphoto_enqueue_styles() {
    // Style du thème parent Astra
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Style du thème enfant (maphoto)
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['parent-style'] 
    );
}
add_action('wp_enqueue_scripts', 'maphoto_enqueue_styles');

// Déclaration des menus
add_action('after_setup_theme', function () {
    register_nav_menus([
        'primary' => 'Header',
        'footer' => 'Footer',
    ]);
});
// Ajout d'un élément externe au menu principal de wp
function add_contact_link_to_menu($items, $args) {// (listes des liens du menu wp, menu ciblé)
    if ($args->theme_location === 'primary') {
        $items .= '<li class="menu-item contact-item">
            <a class="contact-button open-contact">CONTACT</a>
        </li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_contact_link_to_menu', 10, 2);
/*
* On utilise une fonction pour créer notre custom post type 'Séries TV'
*/


function wpm_register_photos_cpt() {

    // CPT "Photos"
    //On crée un tableau avec des clé => valeur pour
    $labels = array(
        'name'               => _x( 'Photos', 'Post Type General Name'), // nom général de mon CPT version pluriel (> 1)
        'singular_name'      => _x( 'Photo', 'Post Type Singular Name'), // nom général de mon CPT version singulier (1 max)
        'menu_name'          => __( 'Photos'),  // nom du menu de mon CPT
        'all_items'          => __( 'Toutes les photos'), // nom de la section qui affiche la liste de mes photos
        'add_new_item'       => __( 'Ajouter une nouvelle photo'), // nom de la section pour ajouter une photo
        'search_items'       => __( 'Rechercher une photo'),
        'not_found'          => __( 'Aucune photo trouvée'),
        'not_found_in_trash' => __( 'Aucune photo dans la corbeille'),
    );

    $args = array(
        'labels'              => $labels, // Associe les labels définis juste avant = Le CPT utilise ces textes pour l’interface

        'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest'        => true,
        'hierarchical'        => false, // les images n'ont pas de contenu enfant
        'public'              => true, // CPT accessible partout (admin et navigateur)
        'has_archive'         => true, // lien page avec toutes les photos disponible dans le cpt
        'rewrite'             => array( 'slug' => 'photos' ), // structure url 
        'menu_position'       => 20, // positionnement du CPT dans l'admin
        'menu_icon'           => 'dashicons-format-image', // illustration du CPT dans admin dashicons-verbe-pour-cibler-icon-dans-bibliotheque-wordpress
    );

    register_post_type( 'photos', $args );

    /**
     * Taxonomie Catégories (Réception, Mariage, etc.)
     */
    $labels_cat = array(
        'name'          => 'Catégories de photos',
        'singular_name' => 'Catégorie de photo',
    );

    register_taxonomy(
        'photo_categorie',
        'photos',
        array(
            'labels'            => $labels_cat,
            'hierarchical'      => true, // active la hierarchie (categorie de photos)
            'show_admin_column' => true, // affiche la catégorie associée à chaque image
            'show_in_rest'      => true, // active Gutenberg
            'public'            => true,
        )
    );

    /**
     * Taxonomie Formats (paysage / portrait)
     */
    $labels_format = array(
        'name'          => 'Formats de photo', 
        'singular_name' => 'Format de photo',
    );

    register_taxonomy(
        'photo_format',
        'photos',
        array(
            'labels'            => $labels_format,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'public'            => true,
            'rewrite'           => array( 'slug' => 'format-photo' ),
        )
    );

    /**
     * Métadonnées exposées à l’API
     * - référence
     * - année
     * - type (argentique / numérique)
     */
    register_post_meta(
        'photos',
        'reference',
        array(
            'type'         => 'string',
            'single'       => true,
            'show_in_rest' => true,
            'auth_callback'=> '__return_true',
        )
    );

    register_post_meta(
        'photos',
        'annee',
        array(
            'type'         => 'number',
            'single'       => true,
            'show_in_rest' => true,
            'auth_callback'=> '__return_true',
        )
    );

    register_post_meta(
        'photos',
        'type_photo', // ex : Argentique / Numérique
        array(
            'type'         => 'string',
            'single'       => true,
            'show_in_rest' => true,
            'auth_callback'=> '__return_true',
        )
    );
}
add_action( 'init', 'wpm_register_photos_cpt',0);


// modale contact

function maphoto_enqueue_scripts() {
     $path =  '/modal.js';
    wp_enqueue_script(
        'maphoto-modal',
        get_stylesheet_directory_uri() .$path,
        [],
        null,
        true // footer
    );

    $path2 = '/mobile-nav.js';
    wp_enqueue_script(
        'maphoto-mobile-nav',
        get_stylesheet_directory_uri() . $path2,
        [],
        null,
        true
    );

    // $path3 = '/dropdown.js';
    // wp_enqueue_script(
    //     'maphoto-dropdown',
    //     get_stylesheet_directory_uri() .$path3,
    //     [],
    //     null,
    //     true
    // );
}
add_action('wp_enqueue_scripts', 'maphoto_enqueue_scripts');


// Ajout de la fonction AJAX 
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');
add_action('wp_ajax_load_more_photos', 'load_more_photos');

function load_more_photos() {

  $offset = intval($_POST['offset']);

  $args = [
    'post_type'      => 'photos',
    'posts_per_page' => 4,
    'offset'         => $offset,
    'post_status'    => 'publish',
    'tax_query'      => []
  ];

  // FILTRE CATEGORIE
  if (!empty($_POST['categorie'])) {
    $args['tax_query'][] = [
      'taxonomy' => 'photo_categorie',
      'field'    => 'slug',
      'terms'    => $_POST['categorie']
    ];
  }

  // FILTRE FORMAT
  if (!empty($_POST['format'])) {
    $args['tax_query'][] = [
      'taxonomy' => 'photo_format',
      'field'    => 'slug',
      'terms'    => $_POST['format']
    ];
  }

  // relation AND si plusieurs filtres
  if (count($args['tax_query']) > 1) {
    $args['tax_query']['relation'] = 'AND';
  }

  // TRI
  if (!empty($_POST['order'])) {
    $args['orderby'] = 'date';
    $args['order']   = $_POST['order'];
  }

  $query = new WP_Query($args);

  if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();

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

      <?php
    endwhile;
  endif;

  wp_reset_postdata();
  wp_die();
}

add_action('wp_ajax_filter_photos', 'filter_photos');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos');

function filter_photos() {

    $args = [
        'post_type' => 'photos',
        'posts_per_page' => 8,
        'tax_query' => []
    ];

    if (!empty($_POST['categorie'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'photo_categorie',
            'field' => 'slug',
            'terms' => $_POST['categorie']
        ];
    }

    if (!empty($_POST['format'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'photo_format',
            'field' => 'slug',
            'terms' => $_POST['format']
        ];
    }

    if (count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    if (!empty($_POST['order'])) {
        $args['orderby'] = 'date';
        $args['order'] = $_POST['order'];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

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
      <img src="http://maphoto.local/wp-content/uploads/2025/12/Group.png">
    </a>

    <img class="open-lightbox"
         src="http://maphoto.local/wp-content/uploads/2026/01/Icon_fullscreen.png">
  </div>
</div>

<?php
        endwhile;
    else :
        echo '<p>Aucune photo trouvée.</p>';
    endif;

    wp_die();
}