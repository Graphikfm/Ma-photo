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
    var_dump($prev_post);
    // On recupe l'url de l'image à la une liée au post (suivant ou precedent)
  $prev_thumb = $prev_post ? get_the_post_thumbnail_url($prev_post, 'medium') : '';
  var_dump($prev_thumb);
  $next_thumb = $next_post ? get_the_post_thumbnail_url($next_post->ID, 'medium') : '';

?>

<section class="single-photo" style="width: 100%; display:flex; justify-content:center;">
    
<div class="container_blocs flex-column" style="width: 80%; display:flex; flex-direction:column;">

    <!-- BLOC 1 -->
    <div class="bloc1" style="display:flex; width:100%; justify-content:center; align-items:flex-end; gap:30px;">
        
        <!-- INFOS -->
        <div style="display:flex; flex-direction:column; width:50%; text-align:left;">
            
            <h1 style="text-transform: uppercase; font-style: italic; font-size: 50px;">
                <?php the_title(); ?>   
            </h1>

            <ul>
                <li style="list-style:none; text-transform: uppercase; margin-bottom:10px;">
                    REFERENCE : <?php echo esc_html($reference); ?>
                </li>

                <?php if ($cats) : ?>
                <li style="list-style:none; text-transform: uppercase; margin-bottom:10px;">
                    CATEGORIE : <?php echo esc_html($cats[0]->name); ?>
                </li>
                <?php endif; ?>

                <?php if ($photoFormat) : ?>
                <li style="list-style:none; text-transform: uppercase; margin-bottom:10px;">
                    FORMAT : <?php echo esc_html($photoFormat[0]->name); ?>
                </li>
                <?php endif; ?>

                <li style="list-style:none; text-transform: uppercase; margin-bottom:10px;">
                    TYPE : <?php echo esc_html($photoType); ?>
                </li>

                <li style="list-style:none; text-transform: uppercase; margin-bottom:10px;">
                    ANNEE : <?php echo esc_html($year); ?>
                </li>
            </ul>

            <span style="width:100%; border:1px solid black;"></span>

        </div>

        <!-- IMAGE PRINCIPALE -->
        <img style="width:50%;" src="<?php echo esc_url($image_url); ?>" alt="">

    </div>

    <!-- BLOC 2 -->
    <div class="bloc2" style="display:flex; justify-content:space-between; width:100%; gap:30px;">

        <!-- CONTACT -->
        <div style="display:flex; width:50%; justify-content:space-between; padding:10px 0; align-items:center;">
            
            <div style="width:50%;">
                <p style="margin:0;">Cette photo vous intéresse ?</p>
            </div>

            <div data-ref="<?php echo esc_attr($reference); ?>" class="open-contact" style="width:50%; padding:10px; text-align:center; background:#d8d8d8; border-radius:5px;">
                Contact
            </div>

        </div>

        <!-- NAVIGATION PREV / NEXT -->
        <div style="width:50%; margin-top:5px; display:flex; justify-content:flex-end; align-items:center;">

            <div class="single-navigation" style="display:flex; gap:40px; width:60%; justify-content:space-between;">

               <div>
                <!--    On recupere grace à la fonction native cpt wp "previous_post_link()" le lien vers (post precedent ou post suivant) -->
                <!--    On crée un dataset thumb avec comme valeur l'url de l'image suivante ou précedente -->

    <?php previous_post_link(
        '%link',
        '<span class="nav-arrow nav-prev" data-thumb="' . esc_url($prev_thumb) . '">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </span>',
        true,
        '',
        'photo_categorie'
    ); ?>
</div>
<img data-src id="miniature" src="" alt="">

<div>
    <?php next_post_link(
        '%link',
        '<span class="nav-arrow nav-next"  data-thumb="' . esc_url($next_thumb) . '">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 6l6 6-6 6"/>
            </svg>
        </span>',
        true,
        '',
        'photo_categorie'
    ); ?>
</div>

            </div>

        </div>

    </div>

    <span style="width:100%; border:1px solid black; margin-top:20px;"></span>

    <!-- VOUS AIMEREZ AUSSI -->
    <div class="bloc3" style="width:100%; display:flex; flex-direction:column;">
        <p>Vous aimerez aussi</p>
    </div>

    <div class="bloc4" style="display:flex; gap:20px; width:80%;">

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

            <div style="width:200px;">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium'); ?>
                </a>
            </div>

        <?php endwhile;
        wp_reset_postdata();
    endif;
}
?>

    </div>

</div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const btnNext = document.querySelector('.nav-next');
    const btnPrev = document.querySelector('.nav-prev');
    const miniature = document.getElementById('miniature');

    if (btnNext) {
        btnNext.addEventListener('mouseover', function() {
            const thumb = this.dataset.thumb;
            if (thumb) {
                miniature.src = thumb;
            }
        });

        btnNext.addEventListener('mouseleave', function() {
            miniature.src = "";
        });
    }

    if (btnPrev) {
        btnPrev.addEventListener('mouseover', function() {
            const thumb = this.dataset.thumb;
            if (thumb) {
                miniature.src = thumb;
            }
        });

        btnPrev.addEventListener('mouseleave', function() {
            miniature.src = "";
        });
    }

});
</script>