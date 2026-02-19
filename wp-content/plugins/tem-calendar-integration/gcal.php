<?php
/**
 * Plugin Name: TEM Calendar Integration
 * Author: StudioTem
 * Description: Load Google Calendar Events using Google Calendar API
 */

/**
 * Define Constants
 */
if(!defined("TEM_GCAL_INTEGRATION_PATH")) {
    define("TEM_GCAL_INTEGRATION_PATH", untrailingslashit(plugin_dir_path( __FILE__ )) );
}
if (!defined("TEM_GCAL_INTEGRATION_URI")) {
    define("TEM_GCAL_INTEGRATION_URI", untrailingslashit(plugin_dir_url( __FILE__ )) );
}

/**
 * Include Admin Settings
 */
require_once TEM_GCAL_INTEGRATION_PATH . '/admin/settings.php';

/**
 * Main Shortcode to load all events
 */
add_shortcode("studiotem_gcal_events", "studiotem_gcal_events_shortcode");
if(!function_exists("studiotem_gcal_events_shortcode")) {
    function studiotem_gcal_events_shortcode() {
        // Get settings from options
        $calendar_id = get_option('studiotem_gcal_calendar_id');
        $api_key = get_option('studiotem_gcal_api_key');
        
        // Check if settings are configured
        if (empty($calendar_id) || empty($api_key)) {
            return '<p>Please configure the Google Calendar settings in the admin panel.<br><br>  To Add Google Api Credentials, Open Admin -> Settings -> TEM Calendar</p>';
        }
        
        $events_endpoint = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events?key={$api_key}";
        $events_endpoint = apply_filters("studiotem_gcal_event_endpoint", $events_endpoint,"");

        // get request to the url 
        $result = wp_remote_get( $events_endpoint );
        if( is_wp_error( $result ) ) {
            return $result;
        }
        $events = json_decode( wp_remote_retrieve_body( $result ), true );
        if( isset( $events["items"] ) ) {
            // $html = "<pre>";
            // $html .= print_r( $events["items"], true );
            // $html .= "</pre>";
            $event_items = $events["items"];
            ob_start();
            require_once plugin_dir_path( __FILE__ ) . "html/gcal-structure.php";
            $html = ob_get_clean();
            return $html;
        }
    }
}

add_action( 'wp_enqueue_scripts', 'studiotem_gcal_enqueues' );
function studiotem_gcal_enqueues() {
    wp_register_style( 'tem-gcal-integration-style', TEM_GCAL_INTEGRATION_URI . '/assets/css/gcal.css', array(), filemtime(TEM_GCAL_INTEGRATION_PATH . '/assets/css/gcal.css') );
    wp_register_script( 'tem-gcal-integration-script', TEM_GCAL_INTEGRATION_URI . '/assets/js/gcal.js', array('jquery'), filemtime(TEM_GCAL_INTEGRATION_PATH . '/assets/js/gcal.js'), true );
    if( is_singular() ) {
        global $post;
        if( has_shortcode( $post->post_content, 'studiotem_gcal_events' ) ) {
            wp_enqueue_style( 'tem-gcal-integration-style' );
            wp_enqueue_script( 'tem-gcal-integration-script' );
        }
    }
}

// dont show the title if its the page 
add_filter( 'the_title', 'studiotem_gcal_hide_page_title', 10, 2 );
function studiotem_gcal_hide_page_title( $title, $id ) {
    if( is_singular() && has_shortcode( get_post_field( 'post_content', $id ), 'studiotem_gcal_events' ) ) {
        return '';
    }
    return $title;
}