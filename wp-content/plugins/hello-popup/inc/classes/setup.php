<?php
namespace HELLOPOPUP\Inc\Classes;

if (! defined('ABSPATH')) {
    exit;
}
use HELLOPOPUP\Inc\Traits\Singleton;

class Setup
{
    use Singleton;

    public function __construct()
    {
        $this->setup_hook();
    }

    public function setup_hook()
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);

        add_action('wp_ajax_save_hello_popup_settings', [$this, 'save_hello_popup_settings']);

    }


    public function add_menu()
    {

        // setting page
        
        add_menu_page(
            __('Hello Popup Settings', "hello-popup"),
            __('Hello Popup', 'hello-popup'),
            'manage_options',
            'hello-popup',
            [$this, 'settings_page'],
           HELLO_POPUP_URL . '/src/images/icon.svg',
            100
        );
    }

    public function register_settings()
    {

        register_setting('hello_popup_group', 'hello_popup_enabled', [
            'type'              => 'boolean',
            'sanitize_callback' => [$this, 'sanitize_enabled'],
        ]);

        register_setting('hello_popup_group', 'hello_popup_auto_show', [
            'type'              => 'boolean',
            'sanitize_callback' => [$this, 'sanitize_enabled'],
        ]);

        register_setting('hello_popup_group', 'hello_popup_title', [

            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => __('Welcome to Our Website!', 'hello-popup'),
        ]);
        register_setting('hello_popup_group', 'hello_popup_message', [

            'type'              => 'string',
            'sanitize_callback' => [$this, 'sanitize_textarea'],
            'default'           => __('We\'re glad you\'re here. Check out our latest updates!', 'hello-popup'),
        ]);
        register_setting('hello_popup_group', 'hello_popup_image',
            [
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );
        register_setting('hello_popup_group', 'hello_popup_delay',
            [
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
                'default'           => 1500,
            ]
        );

        register_setting('hello_popup_settings_group', 'hello_popup_cta_text', 
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => __('Click Here', 'hello-popup'),
            ]
        );
register_setting('hello_popup_settings_group', 'hello_popup_cta_url',
            [
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );

        // Register color setting with default value
        register_setting('hello_popup_settings_group', 'hello_popup_cta_color', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#000', 
        ]);
           register_setting('hello_popup_settings_group', 'hello_popup_close_btn_color', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default'           => '#000', 
        ]);



        register_setting('hello_popup_group', 'hello_popup_shortcode', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        register_setting('hello_popup_group', 'hello_popup_expiry', [
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'default'           => 24,
        ]);
        register_setting('hello_popup_group', 'hello_popup_selected_pages', [
            'type'              => 'array',
            'sanitize_callback' => [$this, 'sanitize_selected_pages']
]);

register_setting('hello_popup_group', 'hello_popup_custom_css', [
    'type'              => 'string',
    'sanitize_callback' => [$this, 'sanitize_custom_css'],
    'default'           => "#hp-modal {}\n.hp-overlay {}\n.hp-container {}\n.hp-content {}\n.hp-cta-button {}\n",

]);


}

    // Custom sanitizer example
    public function sanitize_enabled($value)
    {
        return ($value == 1) ? 1 : 0; // checkbox value either 1 or 0
    }

    public function sanitize_textarea($value)
    {
        return wp_kses_post($value); // allows safe HTML tags in textarea
    }
    public function sanitize_selected_pages($value) {
    return array_map('absint', (array) $value);
    }
    // public function sanitize_custom_css($value) {
    //  return wp_kses($value, []);
    // }

    public function sanitize_custom_css($value) {
        $value = wp_strip_all_tags($value);
    
        if (strlen($value) > 10000) {
            add_settings_error('hello_popup_custom_css', 'css_too_long', __('Custom CSS must be less than 10000 characters.', 'hello-popup'));
            return '';
        }
    
        return $value;
    }
    



    public function settings_page()
    {

        if (! current_user_can('manage_options')) {
            return;
        }

        // Load the settings template
        require_once HELLO_POPUP_DIR . '/templates/settings.php';
    }

    public function save_hello_popup_settings()
    {
        if (
    ! current_user_can('manage_options') ||
    ! isset($_POST['hello_popup_settings_nonce_field']) ||
    ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hello_popup_settings_nonce_field'])), 'hello_popup_settings_nonce')
) {
    wp_send_json_error(['message' => 'Unauthorized request']);
}


        $options = [
            'hello_popup_enabled'   => isset($_POST['hello_popup_enabled']) ? 1 : 0,
            'hello_popup_auto_show' => isset($_POST['hello_popup_auto_show']) ? 1 : 0,
            'hello_popup_title'     => sanitize_text_field(wp_unslash($_POST['hello_popup_title'] ?? '')),
            'hello_popup_message'   => wp_kses_post(wp_unslash($_POST['hello_popup_message'] ?? '')),
            'hello_popup_image'     => esc_url_raw(wp_unslash($_POST['hello_popup_image'] ?? '')),
            'hello_popup_delay'     => absint($_POST['hello_popup_delay'] ?? 1500),

            'hello_popup_cta_text'  => sanitize_text_field(wp_unslash($_POST['hello_popup_cta_text'] ?? __('Click Here', 'hello-popup'))),
            'hello_popup_cta_url'   => esc_url_raw(wp_unslash($_POST['hello_popup_cta_url'] ?? '')),
            'hello_popup_cta_color' => sanitize_hex_color(wp_unslash($_POST['hello_popup_cta_color'] ?? '#ff0000')), 
            'hello_popup_close_btn_color' => sanitize_hex_color(wp_unslash($_POST['hello_popup_close_btn_color'] ?? '#000')), 
            'hello_popup_shortcode' => sanitize_text_field(wp_unslash($_POST['hello_popup_shortcode'] ?? '')),
            'hello_popup_expiry'    => absint($_POST['hello_popup_expiry'] ?? 24),
         'hello_popup_custom_css' => wp_kses(wp_unslash($_POST['hello_popup_custom_css'] ?? ''), []),


        ];

        foreach ($options as $key => $value) {
            update_option($key, $value);
        }
         if (isset($_POST['hello_popup_selected_pages']) && is_array($_POST['hello_popup_selected_pages'])) {
            $selected_pages = array_map('absint', $_POST['hello_popup_selected_pages']);
            update_option('hello_popup_selected_pages', $selected_pages);
        } else {
            update_option('hello_popup_selected_pages', []);
        }

        wp_send_json_success();

    }

}
