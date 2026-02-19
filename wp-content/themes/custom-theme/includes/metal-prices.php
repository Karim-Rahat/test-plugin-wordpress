<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		if ( current_time( 'timestamp' ) - $last_updated < 12 * HOUR_IN_SECONDS ) {
			$should_fetch = false;
			$data = json_decode( $cached_entry->response_json, true );
		}
	}

	if ( ! $should_fetch && ! empty( $data ) ) {
		return $data;
	}

	$api_url = 'https://gold.g.apised.com/v1/latest?metals=XAU%2CXAG%2CXPT%2CXPD&base_currency=KWD&currencies=EUR%2CKWD%2CGBP%2CUSD&weight_unit=gram';
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
		wp_send_json_error( ['message' => $data->get_error_message()] );
	} else {
		wp_send_json( $data );
	}
}
add_action( 'wp_ajax_get_metal_prices', 'custom_theme_ajax_get_metal_prices' );

add_action('rest_api_init', function() {
	register_rest_route('custom-theme/v1', '/metal-prices', [
		'methods'  => 'GET',
		'callback' => 'custom_theme_rest_get_metal_prices',
		'permission_callback' => '__return_true',
	]);
});

function custom_theme_rest_get_metal_prices( $request ) {
	$data = custom_theme_get_metal_prices_data();
	if ( is_wp_error( $data ) ) {
		return new WP_Error( 'api_error', $data->get_error_message(), ['status' => 500] );
	}
	return rest_ensure_response( $data );
}
