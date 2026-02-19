<?php
/**
 * The file that defines the GoFood_Cart class
 *
 * @link  https://moyshan.netlify.app
 * @since 1.0.0
 *
 * @package    GoFood
 * @subpackage GoFood/includes
 */

/**
 * Class GoFood_Cart
 *
 * This class defines all code necessary to manage the cart.
 *
 * @package    GoFood
 * @subpackage GoFood/includes
 * @author     Shahin Moyshan <shahin.moyshan2@gmail.com>
 */
class GoFood_Cart {

	/**
	 * Stores the current cart items.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public static $cart;

	/**
	 * Gets the current cart session.
	 *
	 * @since 1.0.0
	 *
	 * @return array Cart items.
	 */
	public static function get_cart() {

		if ( ! session_id() ) {
			session_start();
		}

		if ( ! isset( self::$cart ) ) {
			self::$cart = isset( $_SESSION['cart'] ) ? json_decode( sanitize_textarea_field( $_SESSION['cart'] ), true ) : array();
		}

		return self::$cart;
	}

	/**
	 * Set the cart session.
	 *
	 * @since 1.0.0
	 *
	 * @param array $cart Cart items.
	 */
	public static function set_cart( $cart ) {
		self::$cart = $cart;
	}

	/**
	 * Empty the current cart session.
	 *
	 * @since 1.0.0
	 */
	public static function empty_cart() {
		self::$cart = array();
	}

	/**
	 * Adds an item to the current cart session.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $product_id Product ID of the item to add.
	 * @param array $item Item to add.
	 */
	public static function add_to_cart( $product_id, $item ) {
		self::get_cart();
		self::$cart[ $product_id ] = $item;
	}

	/**
	 * Checks if a given product is in the cart.
	 *
	 * @since 1.0.0
	 *
	 * @param int $product_id Product ID to check.
	 * @return bool True if the product is in the cart, false otherwise.
	 */
	public function has_cart_item( $product_id ) {
		self::get_cart();
		return isset( self::$cart[ $product_id ] );
	}

	/**
	 * Gets a cart item by product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $product_id Product ID of the item to retrieve.
	 * @return array Cart item.
	 */
	public function get_cart_item( $product_id ) {
		self::get_cart();
		return self::$cart[ $product_id ];
	}

	/**
	 * Removes an item from the current cart session.
	 *
	 * @since 1.0.0
	 * @param int $index Index of the item to remove.
	 */
	public static function remove_from_cart( $index ) {
		self::get_cart();
		unset( self::$cart[ $index ] );
	}

	/**
	 * Saves the current cart session.
	 *
	 * @since 1.0.0
	 */
	public static function save_cart() {
		$_SESSION['cart'] = wp_json_encode( self::get_cart() );
	}

	/**
	 * Returns the instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @return self The instance of the class.
	 */
	public static function instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new self();
		}
		return $instance;
	}
}
