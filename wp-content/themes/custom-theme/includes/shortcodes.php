<?php
/**
 * Custom Shortcodes
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shortcode to display the current year.
 * Usage: [current_year]
 */
function custom_theme_current_year_shortcode() {
	return date( 'Y' );
}
add_shortcode( 'current_year', 'custom_theme_current_year_shortcode' );

