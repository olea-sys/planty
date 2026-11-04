<?php
/**
 * Fonctions du thème enfant Planty.
 * Ce fichier remplace le functions.php actuel du thème enfant.
 */

/* Empêcher l'exécution directe du fichier hors de WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Compléter les fonctions du thème après l'initialisation de BlankSlate. */
add_action( 'after_setup_theme', 'planty_setup', 20 );

function planty_setup() {
    /* Permettre le choix du logo dans Apparence > Personnaliser. */
    add_theme_support( 'custom-logo' );

    /* Conserver le menu principal de BlankSlate et ajouter celui du footer. */
    register_nav_menu( 'planty-footer', __( 'Pied de page Planty', 'planty' ) );
}

/* Charger les ressources après celles du thème parent. */
add_action( 'wp_enqueue_scripts', 'planty_enqueue_styles', 20 );

function planty_enqueue_styles() {
    /* BlankSlate charge déjà le style.css du thème enfant sous ce nom.
       Retirer ce chargement pour ne pas inclure le même fichier deux fois. */
    wp_dequeue_style( 'blankslate-style' );

    /* Conserver le chargement de Syne choisi pour le projet. */
    wp_enqueue_style(
        'planty-fonts',
        'https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap',
        array(),
        null
    );

    /* Changer la version de la feuille à chaque modification du fichier,
       afin que le navigateur récupère le CSS actualisé. */
    $style_path = get_stylesheet_directory() . '/style.css';
    $version    = file_exists( $style_path )
        ? (string) filemtime( $style_path )
        : wp_get_theme()->get( 'Version' );

    /* Charger les styles de Planty après la déclaration de la police. */
    wp_enqueue_style( 'planty-style', get_stylesheet_uri(), array( 'planty-fonts' ), $version );
}

/* Filtrer les objets du menu avant que WordPress génère son HTML.
   Le lien à configurer dans le menu porte la classe planty-menu-admin. */
add_filter( 'wp_nav_menu_objects', 'planty_filter_admin_menu', 10, 2 );

function planty_filter_admin_menu( $items, $args ) {
    /* Appliquer la condition uniquement au menu principal. */
    if ( 'main-menu' !== $args->theme_location ) {
        return $items;
    }

    foreach ( $items as $index => $item ) {
        /* Repérer le lien Admin grâce à sa classe, indépendamment de son titre. */
        if ( ! in_array( 'planty-menu-admin', (array) $item->classes, true ) ) {
            continue;
        }

        if ( ! is_user_logged_in() ) {
            /* Pour un visiteur déconnecté, retirer le lien du HTML envoyé. */
            unset( $items[ $index ] );
        } else {
            /* Construire la bonne adresse du tableau de bord WordPress,
               y compris si le site est installé dans un sous-dossier. */
            $item->url = admin_url();
        }
    }

    /* Renvoyer la liste des liens dans leur ordre initial. */
    return array_values( $items );
}

/* Ajouter les réglages de la frise dans l'interface WordPress. */
add_action( 'customize_register', 'planty_customize_footer' );

function planty_customize_footer( $wp_customize ) {
    /* Regrouper les réglages dans une section dédiée. */
    $wp_customize->add_section(
        'planty_footer',
        array(
            'title'       => __( 'Pied de page Planty', 'planty' ),
            'description' => __( 'La frise apparaît sur l’accueil et sur la page supplémentaire choisie ci-dessous.', 'planty' ),
            'priority'    => 160,
        )
    );

    /* Enregistrer l'identifiant de l'image, plutôt qu'une adresse fixe.
       absint n'accepte qu'un entier positif ou zéro. */
    $wp_customize->add_setting(
        'planty_footer_frieze_image',
        array( 'default' => 0, 'sanitize_callback' => 'absint' )
    );

    /* Autoriser le choix de la frise depuis la médiathèque. */
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'planty_footer_frieze_image',
            array(
                'label'     => __( 'Image de la frise', 'planty' ),
                'section'   => 'planty_footer',
                'mime_type' => 'image',
            )
        )
    );

    /* Enregistrer la page par son identifiant : changer son adresse
       ou son titre ne modifiera pas la règle d'affichage. */
    $wp_customize->add_setting(
        'planty_footer_frieze_page',
        array( 'default' => 0, 'sanitize_callback' => 'absint' )
    );

    /* Proposer un sélecteur de pages plutôt qu'une valeur à modifier en PHP. */
    $wp_customize->add_control(
        'planty_footer_frieze_page',
        array(
            'label'       => __( 'Autre page avec la frise', 'planty' ),
            'description' => __( 'Choisir « Nous rencontrer » pour respecter la maquette. L’accueil est inclus automatiquement.', 'planty' ),
            'section'     => 'planty_footer',
            'type'        => 'dropdown-pages',
        )
    );
}

/* Déterminer si la page actuelle est la deuxième page avec une frise. */
function planty_is_frieze_secondary_page() {
    $page_id = absint( get_theme_mod( 'planty_footer_frieze_page', 0 ) );

    /* Vérifier l'identifiant avant is_page() : une valeur vide désignerait
       sinon n'importe quelle page WordPress. */
    return $page_id > 0 && is_page( $page_id );
}

/* Centraliser la condition d'affichage utilisée par le footer. */
function planty_show_footer_frieze() {
    $image_id = absint( get_theme_mod( 'planty_footer_frieze_image', 0 ) );

    /* Afficher la frise uniquement si une image est sélectionnée,
       sur l'accueil ou sur la page supplémentaire configurée. */
    return $image_id > 0 && ( is_front_page() || planty_is_frieze_secondary_page() );
}
