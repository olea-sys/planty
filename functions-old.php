<?php

/* Charger les polices et les styles du thème
   au moment prévu par WordPress. */
add_action( 'wp_enqueue_scripts', 'planty_enqueue_styles', 20 );

function planty_enqueue_styles() {
    /* Charger Syne depuis Google Fonts,
       avec les graisses de 400 à 800. */
    wp_enqueue_style(
        'planty-fonts',
        'https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap',
        array(),
        null
    );

    /* Charger la feuille de style de Planty
       après la déclaration de la police. */
    wp_enqueue_style(
        'planty-style',
        get_stylesheet_uri(),
        array( 'planty-fonts' ),
        wp_get_theme()->get( 'Version' )
    );
}