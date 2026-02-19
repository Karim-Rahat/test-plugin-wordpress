<?php
/**
 * Fired during theme activation
 *
 * @link  https://moyshan.netlify.app
 * @since 1.0.0
 *
 * @package    GoFood
 * @subpackage GoFood/includes
 */

/**
 * Fired during theme activation.
 *
 * This class defines all code necessary to run during the theme's activation.
 *
 * @since      1.0.0
 * @package    GoFood
 * @subpackage GoFood/includes
 * @author     Shahin Moyshan <shahin.moyshan2@gmail.com>
 */
class GoFood_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {

		// Create orders table.
		GoFood_Query::exec(
			sprintf(
				'CREATE TABLE IF NOT EXISTS `%s` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`first_name` varchar(100) DEFAULT NULL,
						`last_name` varchar(100) DEFAULT NULL,
						`address` varchar(200) DEFAULT NULL,
						`city` varchar(100) DEFAULT NULL,
						`state` varchar(100) DEFAULT NULL,
						`zipcode` varchar(100) DEFAULT NULL,
						`phone` varchar(100) DEFAULT NULL,
						`email` varchar(100) DEFAULT NULL,
						`user_id` bigint(20) unsigned DEFAULT NULL,
						`total` decimal(10,2) DEFAULT NULL,
						`status` enum(\'pending\',\'completed\',\'cancelled\') DEFAULT \'pending\',
						`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY (`id`),
						KEY `fk_gf_user_orders` (`user_id`),
						CONSTRAINT `fk_gf_user_orders` FOREIGN KEY (`user_id`) REFERENCES `%s` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
					)',
				GoFood_Query::table( 'gf_orders' ),
				GoFood_Query::table( 'users' ),
			)
		);

		// Create order items table.
		GoFood_Query::exec(
			sprintf(
				'CREATE TABLE IF NOT EXISTS `%s` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`order_id` int(11) NOT NULL,
						`product_id` bigint(20) unsigned NOT NULL,
						`quantity` int(11) NOT NULL,
						`price` decimal(10,2) NOT NULL,
						PRIMARY KEY (`id`),
						KEY `product_id` (`product_id`),
						KEY `order_id` (`order_id`),
						CONSTRAINT `fk_gf_order_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `%s` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT `fk_gf_order_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `%s` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
					)',
				GoFood_Query::table( 'gf_order_items' ),
				GoFood_Query::table( 'posts' ),
				GoFood_Query::table( 'gf_orders' ),
			)
		);

		// Define the pages we need.
		$pages = array(
			'my-account' => array(
				'title'  => __( 'My Account', 'gofood' ),
				'option' => 'gofood_myaccount_page_id',
			),
			'checkout'   => array(
				'title'  => __( 'Checkout', 'gofood' ),
				'option' => 'gofood_checkout_page_id',
			),
		);

		foreach ( $pages as $slug => $data ) {
			$existing = get_page_by_path( $slug, OBJECT, 'page' );

			if ( $existing ) {
				$page_id = $existing->ID;
			} else {
				// If still no page found, create it.
				$new_page = array(
					'post_title'   => $data['title'],
					'post_name'    => sanitize_title( $slug ),
					'post_content' => '',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				);

				$page_id = wp_insert_post( $new_page );
			}

			// Finally, update the option with the verified page ID.
			update_option( $data['option'], $page_id, true );
		}

		// Refresh the wp rewrite rules.
		flush_rewrite_rules( true );
	}
}
