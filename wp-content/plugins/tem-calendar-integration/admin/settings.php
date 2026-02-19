<?php
/**
 * Admin Settings Page
 */

// Add admin menu
add_action('admin_menu', 'studiotem_gcal_admin_menu');
function studiotem_gcal_admin_menu() {
    add_options_page(
        'Festanca G-Calendar Settings',
        'Festanca G-Calendar',
        'manage_options',
        'tem-calendar-settings',
        'studiotem_gcal_settings_page'
    );
}

// Register settings
add_action('admin_init', 'studiotem_gcal_register_settings');
function studiotem_gcal_register_settings() {
    register_setting('studiotem_gcal_settings_group', 'studiotem_gcal_calendar_id');
    register_setting('studiotem_gcal_settings_group', 'studiotem_gcal_api_key');
}

// Settings page HTML
function studiotem_gcal_settings_page() {
    ?>
    <div class="wrap">
        <h1>Festanca G-Calendar Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('studiotem_gcal_settings_group'); ?>
            <?php do_settings_sections('studiotem_gcal_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Calendar ID</th>
                    <td>
                        <input type="text" name="studiotem_gcal_calendar_id" 
                               value="<?php echo esc_attr(get_option('studiotem_gcal_calendar_id')); ?>" 
                               class="regular-text" />
                        <p class="description">Enter your Google Calendar ID (e.g., example@group.calendar.google.com)</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">API Key</th>
                    <td>
                        <input type="password" name="studiotem_gcal_api_key" 
                               value="<?php echo esc_attr(get_option('studiotem_gcal_api_key')); ?>" 
                               class="regular-text" />
                        <p class="description">Enter your Google Calendar API key</p>
                    </td>
                </tr>

                <tr> 
                    <th>
                        <h2>Display Shortcode</h2>
                    </th>
                    <td>
                        <input type="text" value="[studiotem_gcal_events]" readonly />
                    
                    </td>
                    </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
