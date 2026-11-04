<?php
/** Pied de page commun, avec frise conditionnelle. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
        </main><!-- #content, ouvert dans header.php -->
    </div><!-- #container, ouvert dans header.php -->

    <footer class="planty-site-footer">
        <?php if ( planty_show_footer_frieze() ) : ?>
            <?php
            /* Prévoir un fond beige sur « Nous rencontrer »,
               et un fond blanc sur l'accueil, comme dans la maquette. */
            $frieze_class = 'planty-footer-frieze';
            if ( ! is_front_page() && planty_is_frieze_secondary_page() ) {
                $frieze_class .= ' planty-footer-frieze--beige';
            }
            ?>
            <!-- La frise est décorative : elle n'ajoute pas d'information orale. -->
            <div class="<?php echo esc_attr( $frieze_class ); ?>" aria-hidden="true">
                <?php
                /* WordPress génère une image avec ses dimensions
                   et les variantes disponibles dans la médiathèque. */
                echo wp_get_attachment_image(
                    absint( get_theme_mod( 'planty_footer_frieze_image', 0 ) ),
                    'full',
                    false,
                    array(
                        'class'   => 'planty-footer-frieze-image',
                        'alt'     => '',
                        'loading' => 'lazy',
                        'sizes'   => '100vw',
                    )
                );
                ?>
            </div>
        <?php endif; ?>

        <!-- Le lien Mentions légales est géré dans un menu WordPress distinct. -->
        <nav class="planty-footer-nav" aria-label="<?php esc_attr_e( 'Liens du pied de page', 'planty' ); ?>" itemscope itemtype="https://schema.org/SiteNavigationElement">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'planty-footer',
                    'container'      => false,
                    'menu_class'     => 'planty-footer-menu',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                )
            );
            ?>
        </nav>
    </footer>
</div><!-- #wrapper, ouvert dans header.php -->
<?php
/* Conserver le chargement des scripts et des extensions en bas de page. */
wp_footer();
?>
</body>
</html>
