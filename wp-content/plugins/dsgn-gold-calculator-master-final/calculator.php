<?php

/**
 * @package Gold Calculator
 * @since 1.0
 * Plugin Name: Gold Calculator
 * Description: Gold calculator developed by DSGNUK
 * Author: DSGNUK
 * Author URI: https://dsgnuk.com
 */


/**
 * Exit if accessed directly
 */
if (!defined("ABSPATH")) {
    exit;
}

/**
 * Constants
 */
define("GOLD_CALCULATOR_PATH", untrailingslashit(plugin_dir_path(__FILE__)));
define("GOLD_CALCULATOR_URL", untrailingslashit(plugin_dir_url(__FILE__)));

/**dsd
 * Include Autoloader
 */
require_once GOLD_CALCULATOR_PATH . "/inc/helper/autoload.php";
/**
 * Include template tags
 */
require_once GOLD_CALCULATOR_PATH . "/inc/helper/template-tags.php";

function gold_calculator_wm_get_instance()
{
    return \GOLD_CALCULATOR\Inc\Classes\GCALCULATOR::get_instance();
}

gold_calculator_wm_get_instance();



// Install metal prices table
function custom_theme_install_metal_table() {
    global $wpdb;

    $option_name = 'metal_table_created';
    $table_name  = $wpdb->prefix . 'metal_prices_cache';
    $charset_collate = $wpdb->get_charset_collate();

    if ( get_option( $option_name ) === 'true' ) {
        return;
    }

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        response_json longtext NOT NULL,
        updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );

    update_option( $option_name, 'true' );
}
add_action( 'init', 'custom_theme_install_metal_table' );

// Fetch metal prices with DB caching (12 hours)
function custom_theme_get_metal_prices_data() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'metal_prices_cache';

	$cached_entry = $wpdb->get_row( "SELECT * FROM $table_name ORDER BY updated_at DESC LIMIT 1" );
	$should_fetch = true;
	$data = null;

	if ( $cached_entry ) {
		$last_updated = strtotime( $cached_entry->updated_at );
		if ( current_time( 'timestamp' ) - $last_updated < 6 * HOUR_IN_SECONDS ) {
			$should_fetch = false;
			$data = json_decode( $cached_entry->response_json, true );
		}
	}

	if ( ! $should_fetch && ! empty( $data ) ) {
		return $data;
	}

	$api_url = 'https://gold.g.apised.com/v1/latest?metals=XAU%2CXAG%2CXPT%2CXPD&base_currency=GBP&currencies=EUR%2CKWD%2CGBP%2CUSD&weight_unit=gram';
	$api_key = 'sk_9D2a8185353dD9E464E793B31F46A7507b20D2047dC8fFF0';

	$response = wp_remote_get(
		$api_url,
		[
			'headers' => ['x-api-key' => $api_key],
			'timeout' => 20,
		]
	);

	if ( is_wp_error( $response ) ) {
		return $cached_entry ? json_decode( $cached_entry->response_json, true ) : $response;
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( ! empty( $data ) ) {
		$wpdb->insert(
			$table_name,
			[
				'response_json' => $body,
				'updated_at'    => current_time( 'mysql' ),
			]
		);
	}

	return $data;
}

// AJAX handler
function custom_theme_ajax_get_metal_prices() {
    $data = custom_theme_get_metal_prices_data();

    if ( is_wp_error( $data ) ) {
        wp_send_json_error( [ 'message' => $data->get_error_message() ] );
        return;
    }

    // Sanity check
    if ( empty( $data['data']['metal_prices'] ) ) {
        wp_send_json_error( [ 'message' => 'No metal prices found.' ] );
        return;
    }

	$options = get_option('gold_calc_settings', []);
    $value   = isset($options['percentage_adjustment']) ? floatval($options['percentage_adjustment']) : 10;
	$adjust_percent = $value;
    
	
    $direction      = 'minus'; // use 'plus' or 'minus'

    $multiplier = ( $direction === 'minus' )
        ? ( 1 - ( $adjust_percent / 100 ) )
        : ( 1 + ( $adjust_percent / 100 ) );

    foreach ( $data['data']['metal_prices'] as $metal_code => &$metal_data ) {
        foreach ( $metal_data as $key => &$value ) {
            // Only adjust numeric values (e.g., price_24k, price, etc.)
            if ( is_numeric( $value ) ) {
                $value = round( $value * $multiplier, 5 );
            }
        }
        unset( $value );

		// ✅ Remove 10K gold price
        if ( $metal_code === 'XAU' && isset( $metal_data['price_10k'] ) ) {
            unset( $metal_data['price_10k'] );
        }
    }
    unset( $metal_data );

    // ✅ Example: Ensure price_9k is recalculated from adjusted 24k
    if ( isset( $data['data']['metal_prices']['XAU']['price_24k'] ) ) {
        $price_24k = $data['data']['metal_prices']['XAU']['price_24k'];
        $data['data']['metal_prices']['XAU']['price_9k'] = round( $price_24k * ( 9 / 24 ), 5 );
    }

    wp_send_json( $data );
}
add_action( 'wp_ajax_get_metal_prices', 'custom_theme_ajax_get_metal_prices' );
add_action( 'wp_ajax_nopriv_get_metal_prices', 'custom_theme_ajax_get_metal_prices' );

function get_page_with_shortcode($shortcode_tag='dsgn_gold_gold_form') {
    global $wpdb;

    // Search posts/pages that contain the shortcode
    $query = $wpdb->prepare("
        SELECT ID 
        FROM $wpdb->posts 
        WHERE post_status = 'publish'
        AND post_content LIKE %s
        LIMIT 1
    ", '%[' . $wpdb->esc_like($shortcode_tag) . '%');

    $post_id = $wpdb->get_var($query);

    if ($post_id) {
        return get_permalink($post_id);
    }

    return false;
}

// var_dump(get_page_with_shortcode());