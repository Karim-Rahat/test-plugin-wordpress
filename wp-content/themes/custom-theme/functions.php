<?php
/**
 * Child Theme Functions
 */

// Enqueue parent theme stylesheet
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );
} );

// Add your custom functions below
require_once get_stylesheet_directory() . '/includes/security.php';
require_once get_stylesheet_directory() . '/includes/shortcodes.php';
require_once get_stylesheet_directory() . '/includes/metal-prices.php';

// Ensure metal prices table exists (checking on admin_init for robustness in this dev session)
add_action( 'admin_init', 'custom_theme_install_metal_table' );