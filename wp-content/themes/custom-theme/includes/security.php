<?php
/**
 * Security Enhancements
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Remove WordPress version from wp_head and feeds.
 */
function custom_theme_remove_version() {
	return '';
}
add_filter( 'the_generator', 'custom_theme_remove_version' );

/**
 * Remove unnecessary links from wp_head.
 */
function custom_theme_cleanup_head() {
	// Remove EditURI link.
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove Windows Live Writer Name.
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove WP Generator.
	remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'custom_theme_cleanup_head' );

/**
 * Disable XML-RPC specific methods (Optional - usually handled by plugins but good to have)
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
