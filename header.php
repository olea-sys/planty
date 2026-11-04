<?php
/** En-tête commun aux pages du site. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Reprendre l'encodage et autoriser l'adaptation aux écrans mobiles. -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    /* Conserver le chargement des ressources de WordPress et des extensions. */
    wp_head();
    ?>
</head>
<body <?php body_class(); ?>>
<?php
/* Conserver ce point d'insertion WordPress.
   BlankSlate y ajoute notamment le lien d'accès direct au contenu. */
wp_body_open();
?>
<div id="wrapper" class="hfeed">
    <header class="planty-site-header">
        <div class="planty-brand">
            <?php if ( has_custom_logo() ) : ?>
                <?php
                /* WordPress génère le logo et son lien vers l'accueil. */
                the_custom_logo();
                ?>
            <?php else : ?>
                <!-- Afficher le nom du site tant que le logo n'est pas choisi. -->
                <a class="planty-brand-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Le menu reste administrable dans Apparence > Menus. -->
        <nav class="planty-main-nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'planty' ); ?>" itemscope itemtype="https://schema.org/SiteNavigationElement">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'menu_class'     => 'planty-main-menu',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                )
            );
            ?>
        </nav>
    </header>

    <!-- Ces deux conteneurs sont fermés dans footer.php. -->
    <div id="container">
        <main id="content" class="planty-main-content" tabindex="-1">
