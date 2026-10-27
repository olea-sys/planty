<?php

add_action( 'wp_enqueue_scripts', 'planty_enqueue_styles', 20 );

function planty_enqueue_styles() {
    wp_enqueue_style(
        'planty-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );
}