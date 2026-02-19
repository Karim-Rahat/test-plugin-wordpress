<?php
namespace GOLD_CALCULATOR\Inc\Classes;

if (!defined('ABSPATH')) exit;

use GOLD_CALCULATOR\Inc\Traits\Singleton;

class Settings_page {
    use Singleton;

    protected function __construct() {
        // Add menu page
        add_action('admin_menu', [$this, 'register_settings_page']);
        // Register settings, sections, and fields
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Add settings page to WP admin
     */
    public function register_settings_page() {
        add_options_page(
            'Gold Calculator Settings',          // Page title
            'Gold Calculator',                   // Menu title
            'manage_options',                    // Capability
            'gold-calculator-settings',          // Menu slug
            [$this, 'render_settings_page']      // Callback
        );
    }

    /**
     * Register settings, sections, and fields
     */
    public function register_settings() {
        // Register option in DB with sanitize callback
        register_setting(
            'gold_calculator_settings_group',
            'gold_calc_settings',
            [
                'sanitize_callback' => [$this, 'sanitize_settings'],
            ]
        );

        // ---------------- Form Header Section ----------------
        add_settings_section(
            'form_header_section',
            'Form Header',
            null,
            'gold-calculator-settings'
        );

        add_settings_field(
            'form_header_title',
            'Form Title',
            [$this, 'render_text_field'],
            'gold-calculator-settings',
            'form_header_section',
            ['id' => 'form_header_title', 'placeholder' => 'Enter form title']
        );

        add_settings_field(
            'form_header_subtitle',
            'Form Subtitle',
            [$this, 'render_text_field'],
            'gold-calculator-settings',
            'form_header_section',
            ['id' => 'form_header_subtitle', 'placeholder' => 'Enter form subtitle']
        );

        // ---------------- Price Adjustment Section ----------------
        add_settings_section(
            'gold_calc_adjustment_section',
            'Price Adjustment Settings',
            function() {
                echo '<p>Control how much to increase or decrease metal prices by percentage.</p>';
            },
            'gold-calculator-settings'
        );

        add_settings_field(
            'gold_calc_percentage_adjustment',
            'Price Adjustment (%)',
            [$this, 'percentage_adjustment_section_callback'],
            'gold-calculator-settings',
            'gold_calc_adjustment_section'
        );

        // ---------------- Contact / Instructions Section ----------------
        add_settings_section(
            'form_content_section',
            'Contact Form Additional Content',
            null,
            'gold-calculator-settings'
        );

        add_settings_field(
            'form_instructions',
            'Contact / Instructions',
            [$this, 'render_textarea_field'],
            'gold-calculator-settings',
            'form_content_section',
            ['id' => 'form_instructions', 'rows' => 10, 'placeholder' => 'Enter your instructions...']
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $output = [];

        // Sanitize text fields
        $output['form_header_title']    = sanitize_text_field($input['form_header_title'] ?? '');
        $output['form_header_subtitle'] = sanitize_text_field($input['form_header_subtitle'] ?? '');
        $output['form_instructions']    = wp_kses_post($input['form_instructions'] ?? '');

        // Sanitize percentage adjustment
        $output['percentage_adjustment'] = isset($input['percentage_adjustment'])
            ? min(100, max(0, floatval($input['percentage_adjustment']))) // limit 0–100
            : 10;

        return $output;
    }

    /**
     * Callback for the adjustment section description / input field
     */
    public function percentage_adjustment_section_callback() {
        $options = get_option('gold_calc_settings', []);
        $value   = isset($options['percentage_adjustment']) ? floatval($options['percentage_adjustment']) : 10;

        echo '<input type="number" min="0" max="100" step="0.1" 
                     name="gold_calc_settings[percentage_adjustment]" 
                     value="' . esc_attr($value) . '" 
                     style="width:70px;" /> %';
    }

    /**
     * Render a text input field
     */
    public function render_text_field($args) {
        $options = get_option('gold_calc_settings', []);
        $value = $options[$args['id']] ?? '';
        echo '<input type="text" name="gold_calc_settings[' . esc_attr($args['id']) . ']" 
                    value="' . esc_attr($value) . '" 
                    style="width:100%;" 
                    placeholder="' . esc_attr($args['placeholder']) . '" />';
    }

    /**
     * Render a textarea field with WP Editor
     */
    public function render_textarea_field($args) {
        $options = get_option('gold_calc_settings', []);
        $content = $options[$args['id']] ?? '';
        $editor_id = esc_attr($args['id']);

        wp_editor($content, $editor_id, [
            'textarea_name' => "gold_calc_settings[{$args['id']}]",
            'textarea_rows' => $args['rows'] ?? 10,
            'media_buttons' => false,
            'teeny'         => true,
            'textarea_cols' => 50,
            'tinymce'       => [
                'toolbar1' => 'bold,italic,underline,|,bullist,numlist,|,link,unlink',
            ],
            'quicktags'     => true,
        ]);
    }

    /**
     * Render the admin settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Gold Calculator Settings</h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('gold_calculator_settings_group');
                    do_settings_sections('gold-calculator-settings');
                    submit_button('Save Settings');
                ?>
            </form>
        </div>
        <?php
    }
}