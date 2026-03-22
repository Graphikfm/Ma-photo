<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 

  $cats = get_the_terms(get_the_ID(), 'photo_categorie');
  $reference  = get_post_meta(get_the_ID(), 'reference', true);
  $year       = get_post_meta(get_the_ID(), 'annee', true);
  $photoType  = get_post_meta(get_the_ID(), 'type_photo', true);
  $photoFormat = get_the_terms(get_the_ID(), 'photo_format');
  $ImageDatas = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
  $image_url  = $ImageDatas ? $ImageDatas[0] : '';

  // On recup les post precedant ou suivant au post actuel et dans une meme catégorie -> true, avec la fonction native cpt wp "get_previous_post(true,rien,nom taxonomie)"
  $prev_post = get_previous_post(true, '', 'photo_categorie');
  $next_post = get_next_post(true, '', 'photo_categorie');
    // var_dump($prev_post);
    // On recupe l'url de l'image à la une liée au post (suivant ou precedent)
  $prev_thumb = $prev_post ? get_the_post_thumbnail_url($prev_post, 'medium') : '';
//   var_dump($prev_thumb);
  $next_thumb = $next_post ? get_the_post_thumbnail_url($next_post, 'medium') : '';

?>

<section class="single-photo">
    
<div class="container_blocs flex-column">

    <!-- BLOC 1 -->
    <div class="bloc1">
        
        <!-- INFOS -->
        <div class="container_infos">
            
            <h1 style="">
                <?php the_title(); ?>   
            </h1>

            <ul>
                <li>
                    REFERENCE : <?php /* ON SECURISE EN TRANSFORMANT EN STRING (ideal dans ce contexte de données) */ echo esc_html($reference); ?>
                </li>

                <?php if ($cats) : ?>
                <li>
                    CATEGORIE : <?php echo esc_html($cats[0]->name); ?>
                </li>
                <?php endif; ?>

                <?php if ($photoFormat) : ?>
                <li>
                    FORMAT : <?php echo esc_html($photoFormat[0]->name); ?>
                </li>
                <?php endif; ?>

                <li>
                    TYPE : <?php echo esc_html($photoType); ?>
                </li>

                <li>
                    ANNEE : <?php echo esc_html($year); ?>
                </li>
            </ul>

            <span></span>

        </div>

        <!-- IMAGE PRINCIPALE -->
        <img src="<?php echo esc_url($image_url); ?>" alt="">

    </div>

    <!-- BLOC 2 -->
    <div class="bloc2" style="">

        <!-- CONTACT -->
        <div class="container-contact">
            
            <div style="">
                <p style="margin:0;">Cette photo vous intéresse ?</p>
            </div>

            <div data-ref="<?php echo esc_attr($reference); ?>" class="open-contact">
                Contact
            </div>

        </div>

        <!-- NAVIGATION PREV / NEXT -->
        <div class="container-mini-nav">

            <div class="single-navigation" style="">

                
                    <!--    On recupere grace à la fonction native cpt wp "previous_post_link()" le lien vers (post precedent ou post suivant) -->
                    <!--    On crée un dataset thumb avec comme valeur l'url de l'image suivante ou précedente -->
                    <div class="container-miniature">
                        <img data-src id="miniature" src="" alt="">
                    </div>
                    <div class="arrows-container">
                        <div>
                        <?php previous_post_link(
                            '%link',
                            '<span class="nav-arrow nav-prev" data-thumb="' . esc_url($prev_thumb) . '">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l4 4" /><path d="M5 12l4 -4" /></svg>
                            </span>',
                            true,
                            '',
                            'photo_categorie'
                        ); ?>
                    </div>
                    <div>
                        <?php next_post_link(
                            '%link',
                            '<span class="nav-arrow nav-next"  data-thumb="' . esc_url($next_thumb) . '">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-right"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M15 16l4 -4" /><path d="M15 8l4 4" /></svg>
                            </span>',
                            true,
                            '',
                            'photo_categorie'
                        ); ?>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <span class="bottom-span"></span>

    <!-- VOUS AIMEREZ AUSSI -->
    <div class="bloc3">
        <h3>Vous aimerez aussi</h3>
    </div>

    <div class="bloc4">

<?php
if ($cats) {

    $related_args = array(
        'post_type'      => 'photos',
        'posts_per_page' => 2,
        'orderby'        => 'rand',
        'post__not_in'   => array(get_the_ID()),
        'tax_query'      => array(
            array(
                'taxonomy' => 'photo_categorie',
                'field'    => 'term_id',
                'terms'    => $cats[0]->term_id,
            ),
        ),
    );

    $related_query = new WP_Query($related_args);

    if ($related_query->have_posts()) :
        while ($related_query->have_posts()) :
            $related_query->the_post(); ?>

            <div class="container-related-img">
               <?php get_template_part('template-parts/photo-card'); ?>
            </div>

        <?php endwhile;
        wp_reset_postdata();
    endif;
}
?>

    </div>

</div>
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

<?php endwhile; endif; ?>

<?php get_footer(); ?>

<script>









document.addEventListener("DOMContentLoaded", function() {

    const btnNext = document.querySelector('.nav-next');
    const btnPrev = document.querySelector('.nav-prev');
    const miniature = document.getElementById('miniature');

    miniature.style.opacity = "0";

    if (btnNext) {
        btnNext.addEventListener('mouseover', function() {
            const thumb = this.dataset.thumb;
            if (thumb) {
                miniature.src = thumb;
                miniature.style.opacity = "1";
            }
        });

        btnNext.addEventListener('mouseleave', function() {
            miniature.style.opacity = "0";
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('mouseover', function() {
            const thumb = this.dataset.thumb;
            if (thumb) {
                miniature.src = thumb;
                miniature.style.opacity = "1";
            }
        });

        btnPrev.addEventListener('mouseleave', function() {
            miniature.style.opacity = "0";
        });
    }

});
</script>