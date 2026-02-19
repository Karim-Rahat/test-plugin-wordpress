<?php
/**
 * The file that defines the core theme class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://moyshan.netlify.app
 * @since 1.0.0
 *
 * @package    GoFood
 * @subpackage GoFood/includes
 */

/**
 * The core theme class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this theme as well as the current
 * version of the theme.
 *
 * @since      1.0.0
 * @package    GoFood
 * @subpackage GoFood/includes
 * @author     Shahin Moyshan <shahin.moyshan2@gmail.com>
 */
class GoFood {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the theme.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    GoFood_Loader    $loader    Maintains and registers all hooks for the theme.
	 */
	protected $loader;

	/**
	 * The unique identifier of this theme.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $theme_name    The string used to uniquely identify this theme.
	 */
	protected $theme_name;

	/**
	 * The current version of the theme.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $version    The current version of the theme.
	 */
	protected $version;

	/**
	 * Define the core functionality of the theme.
	 *
	 * Set the theme name and the theme version that can be used throughout the theme.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( defined( 'GOFOOD__VERSION' ) ) {
			$this->version = GOFOOD__VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->theme_name = 'gofood';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this theme.
	 *
	 * Include the following files that make up the theme:
	 *
	 * - GoFood_Loader. Orchestrates the hooks of the theme.
	 * - GoFood_I18n. Defines internationalization functionality.
	 * - GoFood_Admin. Defines all hooks for the admin area.
	 * - GoFood_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core theme.
		 */
		include_once get_template_directory() . '/app/lib/class-gofood-loader.php';

		/**
		 * The class responsible for defining the cart functionality in this theme.
		 */
		include_once get_template_directory() . '/app/lib/class-gofood-cart.php';

		/**
		 * The helper class responsible for performing database queries in this theme.
		 */
		include_once get_template_directory() . '/app/lib/class-gofood-query.php';

		/**
		 * The helper class responsible generating form elements.
		 */
		include_once get_template_directory() . '/app/lib/class-gofood-form.php';

		/**
		 * The helper class responsible performing CRUD/BREAD
		 */
		include_once get_template_directory() . '/app/lib/class-gofood-bread.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		include_once get_template_directory() . '/app/admin/class-gofood-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		include_once get_template_directory() . '/app/public/class-gofood-public.php';

		$this->loader = new GoFood_Loader();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the theme.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {

		$theme_admin = new GoFood_Admin( $this->get_theme_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $theme_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $theme_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'product_category_add_form_fields', $theme_admin, 'custom_product_category_add_image_field' );
		$this->loader->add_action( 'product_category_edit_form_fields', $theme_admin, 'custom_product_category_edit_image_field' );
		$this->loader->add_action( 'admin_enqueue_scripts', $theme_admin, 'custom_product_category_image_enqueue_scripts' );
		$this->loader->add_action( 'created_product_category', $theme_admin, 'custom_save_product_category_image' );
		$this->loader->add_action( 'edited_product_category', $theme_admin, 'custom_save_product_category_image' );
		$this->loader->add_action( 'save_post', $theme_admin, 'custom_save_product_details' );
		$this->loader->add_filter( 'init', $theme_admin, 'create_products_post_type' );
		$this->loader->add_filter( 'init', $theme_admin, 'create_products_category_taxonomy' );
		$this->loader->add_filter( 'add_meta_boxes', $theme_admin, 'add_product_meta_box' );
		$this->loader->add_action( 'admin_menu', $theme_admin, 'add_admin_menu' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the theme.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_public_hooks() {

		$theme_public = new GoFood_Public( $this->get_theme_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $theme_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $theme_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'query_vars', $theme_public, 'add_query_vars' );
		$this->loader->add_filter( 'wp_ajax_gf_cart', $theme_public, 'ajax_manage_cart' );
		$this->loader->add_filter( 'wp_ajax_nopriv_gf_cart', $theme_public, 'ajax_manage_cart' );
		$this->loader->add_filter( 'wp_ajax_gf_checkout', $theme_public, 'ajax_proceed_checkout' );
		$this->loader->add_filter( 'wp_ajax_nopriv_gf_checkout', $theme_public, 'ajax_proceed_checkout' );
		$this->loader->add_filter( 'wp_ajax_gf_auth', $theme_public, 'ajax_login_and_register' );
		$this->loader->add_filter( 'wp_ajax_nopriv_gf_auth', $theme_public, 'ajax_login_and_register' );
		$this->loader->add_filter( 'wp_ajax_ma_update_account', $theme_public, 'ajax_ma_update_account' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the theme used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string    The name of the theme.
	 */
	public function get_theme_name() {
		return $this->theme_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the theme.
	 *
	 * @since  1.0.0
	 * @return GoFood_Loader    Orchestrates the hooks of the theme.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the theme.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the theme.
	 */
	public function get_version() {
		return $this->version;
	}
}
