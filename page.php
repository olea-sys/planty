<?php
/**
 * Modèle des pages Planty.
 * Les titres et les contenus restent dans les blocs Gutenberg.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Utiliser l'en-tête du thème enfant. */
get_header();

/* Afficher le contenu de la page demandée. */
while ( have_posts() ) :
    the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'planty-page' ); ?>>
        <div class="entry-content">
            <?php
            /* Conserver le rendu normal des blocs et des extensions.
               Le H1 est créé dans Gutenberg, sans titre automatique en double. */
            the_content();

            /* Préserver la navigation si un contenu est découpé sur plusieurs pages. */
            wp_link_pages();
            ?>
        </div>
    </article>
    <?php
endwhile;

/* Utiliser le footer du thème enfant, sans colonne latérale de BlankSlate. */
get_footer();
