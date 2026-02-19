<?php
namespace HELLOPOPUP\Inc\Classes;

if (! defined('ABSPATH')) {
    exit;
}
use HELLOPOPUP\Inc\Traits\Singleton;

class Assets
{
    use Singleton;

    public function __construct()
    {
        $this->setup_hooks();
    }

    public function setup_hooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_footer', [$this, 'render_popup_markup']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);


    add_action('wp_head', function () {
    $custom_css = get_option('hello_popup_custom_css', '');

    if (!empty($custom_css)) {
        echo '<style id="hello-popup-custom-css">' . esc_html($custom_css) . '</style>';
    }
});


    }

    public function enqueue_assets()
    {
        wp_enqueue_style('hello-popup-css', HELLO_POPUP_URL . '/dist/css/popup.css', [], filemtime(HELLO_POPUP_DIR . '/dist/css/popup.css'), 'all');
        wp_enqueue_script('hello-popup-js', HELLO_POPUP_URL . '/dist/js/popup.js', [], filemtime(HELLO_POPUP_DIR . '/dist/js/popup.js'), true);


        $current_id = get_queried_object_id();

        wp_localize_script('hello-popup-js', 'HelloPopupData', [
            'enabled' => get_option('hello_popup_enabled'),
            'title'   => get_option('hello_popup_title'),
            'message' => get_option('hello_popup_message'),
            'image'   => get_option('hello_popup_image'),
            'delay'   => get_option('hello_popup_delay', 1500),
            'popup_expiry' => get_option('hello_popup_expiry', 24), 
             'auto_show'    => get_option('hello_popup_auto_show'),
            // 'shortcode' => do_shortcode(get_option('hello_popup_shortcode')),
            'selected_pages' => get_option('hello_popup_selected_pages', []),
            'current_page_id' => $current_id,


        ]);

        // insert the popup data with ajax
        wp_localize_script('hp-insert-data', 'HelloPopupAjax', [
            'nonce'     => wp_create_nonce('hello_popup_nonce'),
            'ajax_url'  => admin_url('admin-ajax.php'),
            'logged_in' => is_user_logged_in(),
            'is_admin'  => current_user_can('administrator'),
            'username'  => wp_get_current_user()->user_login,

        ]);
    }

    // admin assets enqueue
    public function enqueue_admin_assets()
    {

 wp_enqueue_code_editor([
        'type' => 'text/css',
    ]);

    wp_enqueue_script('wp-theme-plugin-editor');
    wp_enqueue_style('wp-codemirror');
        
          // ✅ Load media uploader script
    wp_enqueue_media();
        wp_enqueue_style('hello-popup-admin-css', HELLO_POPUP_URL . '/dist/css/admin.css', [], filemtime(HELLO_POPUP_DIR . '/dist/css/admin.css'), 'all');
        wp_enqueue_script('hello-popup-admin-js', HELLO_POPUP_URL . '/dist/js/admin.js', ['jquery'], filemtime(HELLO_POPUP_DIR . '/dist/js/admin.js'), true);

            wp_enqueue_script('hello-popup-admin-guide', HELLO_POPUP_URL . '/dist/js/guide.js', ['jquery'], filemtime(HELLO_POPUP_DIR . '/dist/js/guide.js'), true);

             wp_enqueue_style('hello-popup-admin-guide-style', HELLO_POPUP_URL . '/dist/css/guide.css', [], filemtime(HELLO_POPUP_DIR . '/dist/css/guide.css'), 'all');

        wp_localize_script('hello-popup-admin-js', 'HelloPopupAdminData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('hello_popup_settings_nonce'),
        ]);
    }

    public function render_popup_markup()
    {

        require_once HELLO_POPUP_DIR . '/templates/popup-template.php';

    }
}