<?php
/**
 * The public-facing functionality of the theme.
 *
 * @link  https://moyshan.netlify.app
 * @since 1.0.0
 *
 * @package    GoFood
 * @subpackage GoFood/public
 */

/**
 * The public-facing functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    GoFood
 * @subpackage GoFood/public
 * @author     Shahin Moyshan <shahin.moyshan2@gmail.com>
 */
class GoFood_Public {

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
	 * @param string $theme_name The name of the theme.
	 * @param string $version     The version of this theme.
	 */
	public function __construct( $theme_name, $version ) {

		$this->theme_name = $theme_name;
		$this->version    = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/vendor/fontawesome/css/all.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->theme_name, get_template_directory_uri() . '/app/public/css/gofood.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/bootstrap.bundle.min.js', array(), $this->version, false );
		wp_enqueue_script( $this->theme_name, get_template_directory_uri() . '/app/public/js/gofood.js', array( 'jquery' ), $this->version, false );

		$l18n = array(
			'ajax_url'        => admin_url( 'admin-ajax.php' ),
			'nonce'           => wp_create_nonce( 'gf_ajax' ),
			'cart_nonce'      => wp_create_nonce( 'gf_ajax_cart' ),
			'checkout_nonce'  => wp_create_nonce( 'gf_ajax_checkout' ),
			'cart_snapshot'   => array_values( GoFood_Cart::get_cart() ),
			'currency_config' => array(
				'symbol'             => '$',
				'number_of_decimals' => 2,
			),
		);

		if ( is_user_logged_in() ) {
			$l18n['ma_profile_nonce'] = wp_create_nonce( 'ma_ajax_profile' );
		} else {
			$l18n['auth_nonce'] = wp_create_nonce( 'gf_ajax_auth' );
		}

		wp_localize_script( $this->theme_name, 'GOFOOD_OBJECT', $l18n );
	}

	/**
	 * Add custom query vars to the public query variables.
	 *
	 * @since 1.0.0
	 * @param array $query_vars Existing query variables.
	 */
	public function add_query_vars( $query_vars ) {
		$query_vars[] = 'auth_active_tab';
		$query_vars[] = 'ma_active_tab';
		$query_vars[] = 'gf_filter_category_id';
		$query_vars[] = 'gf_page';
		$query_vars[] = 'gf_order_success';
		return $query_vars;
	}

	/**
	 * AJAX callback for managing the cart.
	 *
	 * @since 1.0.0
	 */
	public function ajax_manage_cart() {
		check_ajax_referer( 'gf_ajax_cart', 'nonce', true );
		$cart = GoFood_Cart::instance();

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$action     = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

		if ( 'add' === $action ) {
			if ( $cart->has_cart_item( $product_id ) ) {
				$cart_item = $cart->get_cart_item( $product_id );
				$product   = $cart_item['product'];
				$quantity  = $cart_item['quantity'] + 1;
			} else {
				$product  = gf_get_product_snapshot( $product_id );
				$quantity = 1;
			}

			if ( empty( $product ) ) {
				wp_send_json_error( 'Product not found.', 404 );
			}

			if ( $quantity > $product['stock_quantity'] ) {
				wp_send_json_error( 'Product (' . $product['title'] . ') is out of stock.', 404 );
			}

			$cart->add_to_cart(
				$product_id,
				array(
					'quantity' => $quantity,
					'product'  => $product,
				)
			);

			$cart->save_cart();

			wp_send_json(
				array(
					'success' => true,
					'cart'    => array_values( $cart->get_cart() ),
				)
			);

		} elseif ( 'remove' === $action ) {
			$cart->remove_from_cart( $product_id );

			$cart->save_cart();

			wp_send_json(
				array(
					'success' => true,
					'cart'    => array_values( $cart->get_cart() ),
				)
			);
		}

		wp_send_json_error( 'Unknown Cart action.', 404 );
	}

	/**
	 * AJAX callback for proceeding with the checkout process.
	 *
	 * This function checks the AJAX nonce for security, retrieves the current
	 * cart instance, and performs necessary actions to proceed with the
	 * checkout. It handles the checkout operation through an AJAX request.
	 *
	 * @since 1.0.0
	 */
	public function ajax_proceed_checkout() {
		check_ajax_referer( 'gf_ajax_checkout', 'nonce', true );
		$cart = GoFood_Cart::get_cart();

		if ( empty( $cart ) ) {
			wp_send_json_error( 'Cart is empty.', 404 );
		}

		$subtotal = array_reduce(
			$cart,
			function ( $total, $item ) {
				return $total + ( $item['product']['discounted_price'] ?? $item['product']['price'] ) * $item['quantity'];
			},
			0
		);

		$sanitized_field = fn( $input ) => isset( $_POST[ $input ] ) ? sanitize_text_field( wp_unslash( $_POST[ $input ] ) ) : '';

		$order_data = array(
			'first_name' => $sanitized_field( 'first_name' ),
			'last_name'  => $sanitized_field( 'last_name' ),
			'address'    => $sanitized_field( 'address' ),
			'city'       => $sanitized_field( 'city' ),
			'state'      => $sanitized_field( 'state' ),
			'zipcode'    => $sanitized_field( 'zipcode' ),
			'phone'      => $sanitized_field( 'phone' ),
			'email'      => $sanitized_field( 'email' ),
			'user_id'    => is_user_logged_in() ? get_current_user_id() : null,
			'total'      => $subtotal,
			'created_at' => gmdate( 'Y-m-d H:i:s' ),
		);

		$order_id = GoFood_Query::create( 'gf_orders', $order_data );

		if ( ! $order_id ) {
			wp_send_json_error( 'Order creation failed.', 404 );
		}

		$order_items = array_map(
			function ( $item ) use ( $order_id ) {
				return array(
					'order_id'   => $order_id,
					'product_id' => $item['product']['id'],
					'quantity'   => $item['quantity'],
					'price'      => $item['product']['discounted_price'] ?? $item['product']['price'],
				);
			},
			$cart
		);

		$items_stored = GoFood_Query::bulk_create( 'gf_order_items', $order_items );

		if ( ! $items_stored ) {
			// Delete the order.
			GoFood_Query::delete( 'gf_orders', array( 'id' => $order_id ) );

			wp_send_json_error( 'Order item creation failed.', 404 );
		}

		// Reduce product stock.
		foreach ( $order_items as $item ) {
			$quantity = get_post_meta( $item['product_id'], '_product_stock_quantity', true );
			update_post_meta( $item['product_id'], '_product_stock_quantity', $quantity - $item['quantity'] );
		}

		GoFood_Cart::empty_cart();
		GoFood_Cart::save_cart();

		$order_token = wp_json_encode(
			array(
				'order_id' => $order_id,
				'time'     => time(),
			)
		);

		wp_send_json(
			array(
				'success' => true,
				'url'     => gf_get_checkout_url() . '?gf_order_success=' . base64_encode( $order_token ),
			)
		);
	}

	/**
	 * Handle login and registration for AJAX requests.
	 *
	 * This function first checks a nonce to ensure the request is valid.
	 * It then sanitizes the input and checks the request type.
	 * If the request type is 'login', it attempts to log the user in.
	 * If the login fails, it sends a JSON error response with the error message and a 401 status.
	 * If the login succeeds, it sends a JSON response with a success flag and the URL of the my account page.
	 * If the request type is 'register', it will eventually handle registration.
	 * If the request type is unknown, it sends a JSON error response with a generic error message and a 404 status.
	 *
	 * @since 1.0.0
	 */
	public function ajax_login_and_register() {
		check_ajax_referer( 'gf_ajax_auth', 'nonce', true );

		$sanitized_field = fn( $input ) => isset( $_POST[ $input ] ) ? sanitize_text_field( wp_unslash( $_POST[ $input ] ) ) : '';

		$action = $sanitized_field( 'type' );

		if ( 'login' === $action ) {
			$user     = $sanitized_field( 'user' );
			$password = $sanitized_field( 'password' );

			$creds = array(
				'user_login'    => $user,
				'user_password' => $password,
				'remember'      => true,
			);

			$user = wp_signon( $creds, false );

			if ( is_wp_error( $user ) ) {
				wp_send_json_error( $user->get_error_message(), 401 );
			}

			wp_send_json(
				array(
					'success' => true,
					'url'     => gf_get_my_account_url(),
				)
			);
		} elseif ( 'register' === $action ) {
			// Handle registration.
			$email                    = $sanitized_field( 'email' );
			$full_name                = $sanitized_field( 'full_name' );
			[$first_name, $last_name] = explode( ' ', $full_name );

			if ( ! is_email( $email ) ) {
				wp_send_json_error( 'Invalid email address.', 500 );
			} elseif ( email_exists( $email ) ) {
				wp_send_json_error( 'Email already exists.', 500 );
			} elseif ( $sanitized_field( 'password' ) !== $sanitized_field( 'confirm_password' ) || strlen( $sanitized_field( 'password' ) ) < 8 ) {
				wp_send_json_error( 'Invalid password or passwords do not match.', 500 );
			}

			$user_data = array(
				'user_login'           => substr( $email, 0, strpos( $email, '@' ) ),
				'user_email'           => $email,
				'user_pass'            => $sanitized_field( 'password' ),
				'first_name'           => $first_name,
				'last_name'            => $last_name,
				'display_name'         => $full_name,
				'show_admin_bar_front' => 'false',
			);

			$user_id = wp_insert_user( $user_data );

			if ( is_wp_error( $user_id ) ) {
				wp_send_json_error( $user_id->get_error_message(), 500 );
			}

			$creds = array(
				'user_login'    => $user_data['user_login'],
				'user_password' => $user_data['user_pass'],
				'remember'      => true,
			);

			$user = wp_signon( $creds, false );

			if ( is_wp_error( $user ) ) {
				wp_send_json_error( $user->get_error_message(), 401 );
			}

			wp_send_json(
				array(
					'success' => true,
					'url'     => gf_get_my_account_url(),
				)
			);
		}

		wp_send_json_error( 'Unknown Auth action.', 404 );
	}

	/**
	 * Update user account data via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function ajax_ma_update_account() {
		check_ajax_referer( 'ma_ajax_profile', 'nonce', true );

		$sanitized_field = fn( $input ) => isset( $_POST[ $input ] ) ? sanitize_text_field( wp_unslash( $_POST[ $input ] ) ) : '';

		$user_id                  = get_current_user_id();
		$full_name                = $sanitized_field( 'full_name' );
		$password                 = $sanitized_field( 'password' );
		[$first_name, $last_name] = explode( ' ', $full_name );

		// if new password set, then validate old password and also check if new password matches confirm password.
		if ( ! empty( $password ) ) {
			$old_password = $sanitized_field( 'old_password' );

			if ( ! wp_check_password( $old_password, wp_get_current_user()->user_pass, $user_id ) ) {
				wp_send_json_error( 'Invalid old password.', 500 );
			} elseif ( $password !== $sanitized_field( 'confirm_password' ) || strlen( $password ) < 8 ) {
				wp_send_json_error( 'Invalid password or passwords do not match.', 500 );
			}
		}

		$user_data = array(
			'ID'           => $user_id,
			'first_name'   => $first_name,
			'last_name'    => $last_name,
			'display_name' => $full_name,
			'user_login'   => $sanitized_field( 'email' ),
			'user_email'   => $sanitized_field( 'email' ),
			'user_pass'    => $password,
		);

		$user_id = wp_update_user( $user_data );

		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( $user_id->get_error_message(), 500 );
		}

		wp_send_json(
			array(
				'success' => true,
				'url'     => gf_get_my_account_url(),
			)
		);
	}
}
