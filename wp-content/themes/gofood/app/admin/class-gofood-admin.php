<?php
/**
 * The admin-specific functionality of the theme.
 *
 * @link  https://moyshan.netlify.app
 * @since 1.0.0
 *
 * @package    GoFood
 * @subpackage GoFood/admin
 */

/**
 * The admin-specific functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    GoFood
 * @subpackage GoFood/admin
 * @author     Shahin Moyshan <shahin.moyshan2@gmail.com>
 */
class GoFood_Admin {

	/**
	 * The ID of this theme.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $theme_name    The ID of this theme.
	 */
	private $theme_name;

	/**
	 * The version of this theme.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this theme.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $theme_name The name of this theme.
	 * @param string $version     The version of this theme.
	 */
	public function __construct( $theme_name, $version ) {

		$this->theme_name = $theme_name;
		$this->version    = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in GoFood_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GoFood_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->theme_name, get_template_directory_uri() . '/app/admin/css/gofood-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in GoFood_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GoFood_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->theme_name, get_template_directory_uri() . '/app/admin/js/gofood-admin.js', array( 'jquery' ), $this->version, false );

		$l18n = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		);

		wp_localize_script( $this->theme_name, 'GOFOOD_OBJECT', $l18n );
	}

	/**
	 * Add the "Manage Orders" menu page to the WordPress admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {

		require_once __DIR__ . '/class/class-gofood-orders.php';

		add_menu_page( 'Orders', 'Manage Orders', 'manage_options', 'gofood-orders', array( GoFood_Orders::class, 'manage' ), 'dashicons-cart', 5 );
	}

	/**
	 * Register the custom post type for products.
	 *
	 * This function sets up the custom post type 'product' with various
	 * labels and arguments for use in the WordPress admin and front-end.
	 * It defines how products are displayed and managed, including support
	 * for features like title, editor, thumbnail, comments, and custom fields.
	 * The function also associates the post type with the 'product_category'
	 * taxonomy and determines its visibility and behavior in the WordPress
	 * interface and REST API.
	 *
	 * @since 1.0.0
	 */
	public function create_products_post_type() {
		$labels = array(
			'name'           => __( 'Products', 'gofood' ),
			'singular_name'  => __( 'Product', 'gofood' ),
			'menu_name'      => __( 'Food Products', 'gofood' ),
			'name_admin_bar' => __( 'Product', 'gofood' ),
			'all_items'      => __( 'All Products', 'gofood' ),
			'add_new_item'   => __( 'Add New Product', 'gofood' ),
			'add_new'        => __( 'Add New', 'gofood' ),
			'new_item'       => __( 'New Product', 'gofood' ),
			'edit_item'      => __( 'Edit Product', 'gofood' ),
		);

		$args = array(
			'labels'        => $labels,
			'public'        => true,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-carrot',
			'supports'      => array( 'title', 'thumbnail', 'excerpt' ),
			'rewrite'       => array( 'slug' => 'product' ),
			'show_in_rest'  => true,
		);

		register_post_type( 'product', $args );
	}

	/**
	 * Registers the custom taxonomy for product categories.
	 *
	 * This function creates a hierarchical taxonomy for organizing
	 * products into categories. It defines the labels, arguments, and
	 * behavior for the taxonomy in the WordPress admin and front-end.
	 *
	 * @since 1.0.0
	 */
	public function create_products_category_taxonomy() {

		$labels = array(
			'name'              => _x( 'Product Categories', 'taxonomy general name', 'gofood' ),
			'singular_name'     => _x( 'Product Category', 'taxonomy singular name', 'gofood' ),
			'search_items'      => __( 'Search Product Categories', 'gofood' ),
			'all_items'         => __( 'All Product Categories', 'gofood' ),
			'parent_item'       => __( 'Parent Product Category', 'gofood' ),
			'parent_item_colon' => __( 'Parent Product Category:', 'gofood' ),
			'edit_item'         => __( 'Edit Product Category', 'gofood' ),
			'update_item'       => __( 'Update Product Category', 'gofood' ),
			'add_new_item'      => __( 'Add New Product Category', 'gofood' ),
			'new_item_name'     => __( 'New Product Category Name', 'gofood' ),
			'menu_name'         => __( 'Product Categories', 'gofood' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product-category' ),
			'show_in_rest'      => true,
		);
		register_taxonomy( 'product_category', array( 'product' ), $args );
	}

	/**
	 * Adds a meta box to the product post type for product details.
	 *
	 * The meta box is titled "Product Details" and is displayed on the
	 * product post type edit screen. It is used to store additional
	 * product details such as price, weight, and nutritional information.
	 *
	 * @since 1.0.0
	 */
	public function add_product_meta_box() {
		add_meta_box(
			'product_details_meta_box',
			__( 'Product Details', 'gofood' ),
			fn( $post ) => $this->custom_product_details_metabox( $post ),
			'product'
		);
	}

	/**
	 * Outputs the HTML for the custom product details meta box.
	 *
	 * This function is a callback for the add_meta_box() function and is used to
	 * render the custom meta box for the product post type. It provides fields for
	 * price, discounted price, and stock quantity.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function custom_product_details_metabox( $post ) {
		// Add nonce for security.
		wp_nonce_field( 'custom_product_details_nonce_action', 'custom_product_details_nonce' );

		// Retrieve existing values from the database.
		$price    = get_post_meta( $post->ID, '_product_price', true );
		$discount = get_post_meta( $post->ID, '_product_discount_price', true );
		$stock    = get_post_meta( $post->ID, '_product_stock_quantity', true );

		// Ensure values are set (fallback to empty).
		$price    = $price ? $price : '';
		$discount = $discount ? $discount : '';
		$stock    = $stock ? $stock : '';

		include gf_partials_path( 'product-custom-meta-box', false, true );
	}


	/**
	 * Enqueues scripts and styles for the custom image upload field on the product category taxonomy page.
	 *
	 * The function is a callback for the `admin_enqueue_scripts` action hook and is used to enqueue the
	 * WordPress media uploader scripts and styles on the taxonomy term pages. It also includes an inline
	 * script for handling the media uploader.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook The current page hook.
	 */
	public function custom_product_category_image_enqueue_scripts( $hook ) {
		// Load scripts only on the taxonomy term pages.
		if ( 'edit-tags.php' === $hook || 'term.php' === $hook ) {
			$screen = get_current_screen();
			if ( isset( $screen->taxonomy ) && 'product_category' === $screen->taxonomy ) {
				wp_enqueue_media();
			}
		}
	}

	/**
	 * Adds an image upload field to the product category custom meta box.
	 *
	 * This function is a callback for the `{$taxonomy}_add_form_fields` action hook and is used
	 * to add an image upload field to the custom meta box for the product category taxonomy.
	 * The image is uploaded to the server and the URL is stored in the database.
	 *
	 * @since 1.0.0
	 */
	public function custom_product_category_add_image_field() {

		// Include the custom meta box template.
		include gf_partials_path( 'product-category-custom-meta-box', false, true );

		wp_nonce_field( 'custom_product_category_image_nonce_action', 'custom_product_category_image_nonce' );
	}

	/**
	 * Renders the custom meta box for the product category taxonomy with an image upload field.
	 *
	 * This function is a callback for the `{$taxonomy}_edit_form_fields` action hook and is used
	 * to add an image upload field to the custom meta box for the product category taxonomy.
	 * The image is uploaded to the server and the URL is stored in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Term $term     The current term object.
	 */
	public function custom_product_category_edit_image_field( $term ) {
		// Get the existing image ID for this term (if any).
		$image_id  = get_term_meta( $term->term_id, 'product_category_image_id', true );
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';

		// Include the custom meta box template.
		include gf_partials_path( 'product-category-custom-meta-box-edit', false, true );

		// Nonce field for security.
		wp_nonce_field( 'custom_product_category_image_nonce_action', 'custom_product_category_image_nonce' );
	}

	/**
	 * Saves the image upload field for the product category taxonomy.
	 *
	 * This function is a callback for the `edited_product_category` action hook and is used to save the
	 * image upload field for the product category taxonomy. It verifies the nonce and then saves or deletes
	 * the term meta depending on whether the image ID is set or not.
	 *
	 * @since 1.0.0
	 *
	 * @param int $term_id The term ID.
	 */
	public function custom_save_product_category_image( $term_id ) {
		// Verify the nonce.
		if ( ! isset( $_POST['custom_product_category_image_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['custom_product_category_image_nonce'] ) ), 'custom_product_category_image_nonce_action' ) ) {
			return;
		}

		// Save or delete the term meta.
		if ( isset( $_POST['product_category_image_id'] ) && '' !== $_POST['product_category_image_id'] ) {
			$image_id = intval( $_POST['product_category_image_id'] );
			update_term_meta( $term_id, 'product_category_image_id', $image_id );
		} else {
			delete_term_meta( $term_id, 'product_category_image_id' );
		}
	}

	/**
	 * Save custom product details.
	 *
	 * This function is a callback for the save_post action and is used to
	 * save the custom product details meta box. It checks for the custom
	 * nonce and user permissions before saving the data to the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 */
	public function custom_save_product_details( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['custom_product_details_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['custom_product_details_nonce'] ) ), 'custom_product_details_nonce_action' ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user permissions.
		if ( isset( $_POST['post_type'] ) && 'product' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		} else {
			return;
		}

		// Sanitize and save Price.
		if ( isset( $_POST['product_price'] ) ) {
			$price = sanitize_text_field( wp_unslash( $_POST['product_price'] ) );
			// Strip out anything that's not a number or decimal point.
			$price = preg_replace( '/[^0-9\.]/', '', $price );
			update_post_meta( $post_id, '_product_price', $price );
		}

		// Sanitize and save Discounted Price.
		if ( isset( $_POST['product_discount_price'] ) ) {
			$discount = sanitize_text_field( wp_unslash( $_POST['product_discount_price'] ) );
			$discount = preg_replace( '/[^0-9\.]/', '', $discount );
			update_post_meta( $post_id, '_product_discount_price', $discount );
		}

		// Sanitize and save Stock Quantity.
		if ( isset( $_POST['product_stock_quantity'] ) ) {
			$stock = sanitize_text_field( wp_unslash( $_POST['product_stock_quantity'] ) );
			$stock = preg_replace( '/[^0-9]/', '', $stock );
			update_post_meta( $post_id, '_product_stock_quantity', $stock );
		}
	}
}
