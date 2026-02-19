<?php
/*
Plugin Name: eZWay Voting System
Plugin URI: https://webermelon.com
Description: A Customizable Contest Voting System for WordPress
Version: 1.1.0
Text Domain: cvs
Author: WEBERMELON
Author URI: https://webermelon.com
License: A "Slug" license name e.g. GPL2
*/

if (! defined('ABSPATH')) {
    die('Invalid request.');
}

define("cvs_plugin_dir_path", plugin_dir_path(__FILE__));
define("cvs_plugin_dir_url", plugin_dir_url(__FILE__));

require_once plugin_dir_path(__FILE__) . "user/cvs_shortcodes.php";

$GLOBALS["cvs_directory_root_url"]  = plugin_dir_url(__FILE__);
$GLOBALS["cvs_directory_root_path"] = plugin_dir_path(__FILE__);

// get cvs settings
function cvs_init_envireonment_keys()
{
    $settings = get_option('cvs_custom_environment', []);
    $object   = json_decode(
        json_encode(
            array_merge($settings, [
                "paypal_api" => cvs_decryptData($settings['paypal_api'] ?? ''),
                "stripe_sk"  => cvs_decryptData($settings['stripe_sk'] ?? ''),
                "stripe_pk"  => cvs_decryptData($settings['stripe_pk'] ?? ''),
            ])
        )
    );

    $GLOBALS['cvs_settings'] = $object;
}

add_action('init', 'cvs_init_envireonment_keys');

function cvs_activation_hook()
{
    if (! function_exists('dbDelta')) {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    }

    global $wpdb;

    $contest_table    = $wpdb->prefix . "cvs_contests";
    $contestant_table = $wpdb->prefix . "cvs_contestants";
    $voter_table      = $wpdb->prefix . "cvs_voters";
    $payments_table   = $wpdb->prefix . "cvs_payments";
    $nominees_table   = $wpdb->prefix . "cvs_nominees";
    $charset_collate  = $wpdb->get_charset_collate();

    $table1 = "CREATE TABLE IF NOT EXISTS $contest_table (
        id integer(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        categories TEXT NULL,
        description TEXT,
        PRIMARY KEY (id)
        ) $charset_collate;";

    $table2 = "CREATE TABLE IF NOT EXISTS $contestant_table (
        id INTEGER (9) NOT NULL AUTO_INCREMENT,
        contest_id INTEGER (9) NOT NULL,
        name varchar(100) NOT NULL,
        category varchar(100) NULL,
        image varchar(255) NOT NULL,
        description TEXT NOT NULL,
        PRIMARY KEY (id)
        ) $charset_collate;";

    $table3 = "CREATE TABLE IF NOT EXISTS $voter_table (
        id integer(9) NOT NULL AUTO_INCREMENT,
        contest_id INTEGER (9) NOT NULL,
        contestant_id INTEGER (9) NOT NULL,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(100) NOT NULL,
        street varchar(200) NOT NULL,
        city varchar(50) NOT NULL,
        state varchar(50) NOT NULL,
        zip varchar(50) NOT NULL,
        country varchar(50) NOT NULL,
        number_of_vote INTEGER NOT NULL,
        status boolean default 0,
        PRIMARY KEY (id)
        ) $charset_collate;";

    $table4 = "CREATE TABLE IF NOT EXISTS $payments_table (
        id integer(9) NOT NULL AUTO_INCREMENT,
        contest_id INTEGER (9) NOT NULL,
        contestant_id INTEGER (9) NOT NULL,
        voter_id INTEGER (9) NOT NULL,
        amount decimal(10,2) NOT NULL,
        currency varchar(50),
        payment_id varchar(255),
        method varchar(50) NOT NULL,
        status enum('pending', 'paid', 'unpaid', 'failed') NOT NULL,
        created_at timestamp default current_timestamp,
        PRIMARY KEY (id)
        ) $charset_collate;";

    $table5 = "CREATE TABLE IF NOT EXISTS $nominees_table (
        id integer(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        image varchar(255) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(100) NOT NULL,
        category varchar(100) NULL,
        created_at timestamp default current_timestamp,
        PRIMARY KEY (id)
        ) $charset_collate;";

    dbDelta($table1);
    dbDelta($table2);
    dbDelta($table3);
    dbDelta($table4);
    dbDelta($table5);

    // Create settings file
    $setting_data = [
        "paypal_api"       => cvs_encryptData("REPLACE_WITH_YOUR_PAYPAL_CLIENT_ID"),
        "stripe_sk"        => cvs_encryptData("REPLACE_WITH_YOUR_STRIPE_SECRETE_KEY"),
        "stripe_pk"        => cvs_encryptData("REPLACE_WITH_YOUR_STRIPE_PUBLISHABLE_KEY"),
        "paypal_active"    => "on",
        "stripe_active"    => "on",
        "voter_pagination" => 20,
    ];

    if (! get_option('cvs_custom_environment', false)) {
        update_option('cvs_custom_environment', $setting_data, true);
    }

    update_option('cvs_custom_url_changed', true);
}
register_activation_hook(__FILE__, 'cvs_activation_hook');

function cvs_load_admin_assets($screen)
{
    $accept = [
        "contestvotingsystem",
        "cvsnewcontest",
        "cvseditcontest",
        "cvsvotes",
        "cvssettings",
        "cvsnominees",
    ];
    $screen = explode("_", $screen);
    $screen = end($screen);

    if (in_array($screen, $accept)) {
        wp_enqueue_style("cvs_bootstrap_css", "https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css");
        wp_enqueue_style("cvs_style", plugin_dir_url(__FILE__) . "/assets/css/style.css");
        wp_enqueue_script("cvs_script", plugin_dir_url(__FILE__) . "/assets/js/script.js");
        wp_enqueue_script('jquery');
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'cvs_load_admin_assets');

function cvs_all_contests_page()
{
    include_once plugin_dir_path(__FILE__) . "/admin/cvs_contests.php";
}

function cvs_new_contest_page()
{
    include_once plugin_dir_path(__FILE__) . "/admin/cvs_new_contest.php";
}

function cvs_votes_page()
{
    include_once plugin_dir_path(__FILE__) . "/admin/cvs_votes.php";
}

function cvs_nominees_page()
{
    include_once plugin_dir_path(__FILE__) . "/admin/cvs_nominees.php";
}

function cvs_settings_page()
{
    include_once plugin_dir_path(__FILE__) . "/admin/cvs_settings.php";
}

function create_cvs_admin_menu()
{
    add_menu_page("All Contests", "eZWay Voting System", "manage_options", 'contestvotingsystem', "cvs_all_contests_page", plugin_dir_url(__FILE__) . "assets/img/icon.svg");
    remove_submenu_page("contestvotingsystem", "contestvotingsystem");
    add_submenu_page("contestvotingsystem", "Contests", "All Contests", "manage_options", "contestvotingsystem", "cvs_all_contests_page");
    add_submenu_page("contestvotingsystem", "Add new contest", "Add New", "manage_options", "cvsnewcontest", "cvs_new_contest_page");
    add_submenu_page("contestvotingsystem", "Votes", "Votes", "manage_options", "cvsvotes", "cvs_votes_page");
    add_submenu_page("contestvotingsystem", "Nominees", "Nominees", "manage_options", "cvsnominees", "cvs_nominees_page");
    add_submenu_page("contestvotingsystem", "CVS Settings", "Settings", "manage_options", "cvssettings", "cvs_settings_page");
}
add_action('admin_menu', 'create_cvs_admin_menu');

function cvs_window_redirect($url)
{
    echo "<script type='text/javascript'>
        window.location.href = '$url';
    </script>";
    exit;
}

/**
 * Encrypt a string
 * @param string $data
 * @return string
 */
function cvs_encryptData($data)
{
    $key = hash('sha256', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
    $iv  = substr(hash('sha256', '9f86d081884c7d659a2feaa0c55ad015'), 0, 16);

    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($encrypted);
}

/**
 * Decrypt a string
 * @param string $encryptedData
 * @return string
 */
function cvs_decryptData($encryptedData)
{
    $key = hash('sha256', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');
    $iv  = substr(hash('sha256', '9f86d081884c7d659a2feaa0c55ad015'), 0, 16);

    $decrypted = openssl_decrypt(base64_decode($encryptedData), 'AES-256-CBC', $key, 0, $iv);

    if ($decrypted) {
        return $decrypted;
    }

    return $decrypted === "0" ? "0" : $encryptedData;
}

add_action('init', function () {
    $export = $_GET['__cvs_export_all_nominee'] ?? '';
    if ('csv' === $export) {
        global $wpdb;

        $nominee_table = $wpdb->prefix . 'cvs_nominees';
        $nominees_data = $wpdb->get_results("SELECT id, name, email, phone, category, created_at FROM {$nominee_table} ORDER BY id DESC", ARRAY_A);

        $csv_name = sprintf('nomine_submission_list_%s', gmdate('d-M')) . '.csv';

        cvs_array_to_csv_download($nominees_data, $csv_name);
        exit;
    }
});

function cvs_array_to_csv_download($array, $filename = "export.csv")
{
    // Clear any previous output
    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    $csv = fopen('php://output', 'w');

    // put csv header
    fputcsv($csv, array_map('ucwords', array_keys($array[0])));

    // put csv data
    foreach ($array as $line) {
        fputcsv($csv, $line);
    }
}