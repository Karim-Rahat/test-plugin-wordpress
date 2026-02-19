<?php

/**
 * Plugin Name:       Hello Popup
 * Description:       Display a customizable popup after a delay. All content is editable from the WordPress admin.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            gazirahad7
 * Author URI:        https://profiles.wordpress.org/gazirahad7
* License: GPLv3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
* Text Domain:       hello-popup

 */

// Exit if accessed directly
if (! defined("ABSPATH")) {
    exit;
}

// Define constants

if (! defined('HELLO_POPUP_FILE')) {
    define('HELLO_POPUP_FILE', __FILE__);
}
if (! defined('HELLO_POPUP_DIR')) {
    define('HELLO_POPUP_DIR', untrailingslashit(plugin_dir_path(HELLO_POPUP_FILE)));
}
if (! defined('HELLO_POPUP_URL')) {
    define('HELLO_POPUP_URL', untrailingslashit(plugin_dir_url(HELLO_POPUP_FILE)));
}

if (! defined('HELLO_POPUP_TEXT_DOMAIN')) {
    define('HELLO_POPUP_TEXT_DOMAIN', 'hello-popup');
}

// Include the loader
require_once HELLO_POPUP_DIR . '/inc/utility/autoload.php';

// Activation Hook
register_activation_hook(__FILE__, 'hello_popup_activate');
function hello_popup_activate()
{
    $defaults = [
        'hello_popup_enabled' => 1,
        'hello_popup_auto_show' => 1,
        'hello_popup_expiry'  => 24, // in hours
        'hello_popup_title'   => 'Welcome to Our Website!',
        'hello_popup_message' => 'We\'re glad you\'re here. Check out our latest updates!',
        'hello_popup_image'   => '',
        'hello_popup_delay'   => 1500,
    ];

    foreach ($defaults as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }

    // Set a flag for redirect after activation
add_option('hello_popup_redirect_after_activation', true);

}

// for redirect after activation guide
add_action('admin_init', 'hello_popup_redirect_after_activation');
function hello_popup_redirect_after_activation() {
    if (get_option('hello_popup_redirect_after_activation')) {
        delete_option('hello_popup_redirect_after_activation');
                    
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Safe redirect on plugin activation

        if (!isset($_GET['activate-multi'])) {
            wp_safe_redirect(admin_url('admin.php?page=hello-popup&guide=1'));
            exit;
        }
    }
}


function hellopopup_get_instance()
{
    return \HELLOPOPUP\Inc\Classes\Init::get_instance();
}
hellopopup_get_instance();







