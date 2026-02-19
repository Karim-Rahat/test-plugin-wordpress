<?php
/**
 * GoFood functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package goFood
 */

if ( ! defined( 'GOFOOD__VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'GOFOOD__VERSION', '1.0.0' );
}

if ( ! defined( 'GOFOOD__THEME_DIR' ) ) {
	// Define the template root directory.
	define( 'GOFOOD__THEME_DIR', WP_CONTENT_DIR . '/themes/gofood' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gofood_setup() {

	/**
	 * Make theme available for translation.
	 * Translators: If you're translating this theme, you'll need to change the
	 * text domain. You can do that here: https://www.potenzaglobalsolutions.com/gofood/
	 * If you're building a theme based on GoFood, use gofood as the text domain
	 * when translating.
	 */
	load_theme_textdomain(
		'gofood',
		get_template_directory() . '/languages'
	);

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );

	/*
	* Enable support for Post Thumbnails on posts and pages.
	*
	* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'gofood' ),
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 200,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'gofood_setup' );

/**
 * Enqueue scripts and styles.
 */
function gofood_scripts() {
	wp_enqueue_style( 'gofood-style', get_stylesheet_uri(), array(), GOFOOD__VERSION );
	wp_enqueue_script( 'gofood-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), GOFOOD__VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gofood_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Bootstrap the theme
 */
require get_template_directory() . '/inc/bootstrap-gofood-theme.php';

/**
 * Bootstrap 5 wp_nav_menu walker
 */
require get_template_directory() . '/inc/bootstrap-navwalker.php';

/**
 * Helper functions
 */
require get_template_directory() . '/inc/gofood-helpers.php';
