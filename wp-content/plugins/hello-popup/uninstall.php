<?php
// If uninstall not called from WordPress, exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Clean up options from the options table
delete_option('hello_popup_enabled');
delete_option('hello_popup_auto_show');
delete_option('hello_popup_title');
delete_option('hello_popup_message');
delete_option('hello_popup_image');
delete_option('hello_popup_delay');
delete_option('hello_popup_cta_text');
delete_option('hello_popup_cta_url');
delete_option('hello_popup_cta_color');
delete_option('hello_popup_shortcode');
delete_option('hello_popup_expiry');
delete_option('hello_popup_selected_pages');
delete_option('hello_popup_custom_css');
delete_option('hello_popup_close_btn_color');




// Clean up any custom database tables if they exist

// multisite

if (is_multisite()) {
    $sites = get_sites();

    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);

    delete_option('hello_popup_enabled');
    delete_option('hello_popup_auto_show');
    delete_option('hello_popup_title');
    delete_option('hello_popup_message');
    delete_option('hello_popup_image');
    delete_option('hello_popup_delay');
    delete_option('hello_popup_cta_text');
    delete_option('hello_popup_cta_url');
    delete_option('hello_popup_cta_color');
    delete_option('hello_popup_shortcode');
    delete_option('hello_popup_expiry');
    delete_option('hello_popup_selected_pages');
    delete_option('hello_popup_custom_css');
    delete_option('hello_popup_close_btn_color');
    
        restore_current_blog();
    }
}