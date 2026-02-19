<?php
/**
 * Form Submission Handler
 * 
 * @package DSGN_Gold_Calculator
 * @subpackage Classes
 * @since 1.0
 */

namespace GOLD_CALCULATOR\Inc\Classes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Form_Submission
 */
use GOLD_CALCULATOR\Inc\Traits\Singleton;
class Form_submission {
   use Singleton;
    /**
     * Constructor
     */
    public function __construct() {
        // Register AJAX actions
        add_action('wp_ajax_gold_sell_form', array($this, 'handle_submission'));
        add_action('wp_ajax_nopriv_gold_sell_form', array($this, 'handle_submission'));
    }

    /**
     * Handle Gold Sell Form Submission
     * 
     * @return void
     */
public function handle_submission() {


    // Check POST content


    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gold_sell_action')) {

        wp_send_json_error(array('message' => 'Security check failed'), 403);
    }


    // Decode JSON fields if needed
    try {
        if (isset($_POST['items']) && is_string($_POST['items'])) {
            $_POST['items'] = json_decode(stripslashes($_POST['items']), true);
        }
        if (isset($_POST['totals']) && is_string($_POST['totals'])) {
            $_POST['totals'] = json_decode(stripslashes($_POST['totals']), true);
        }
        if (isset($_POST['contact']) && is_string($_POST['contact'])) {
            $decoded_contact = json_decode(stripslashes($_POST['contact']), true);
            $_POST = array_merge($_POST, $decoded_contact);
        }
    } catch (Exception $e) {

        wp_send_json_error(['message' => 'Invalid JSON received'], 400);
    }

    // Sanitize and collect contact info

    $contact = $this->sanitize_contact_data($_POST);


    // Validate contact info
    $validation = $this->validate_contact_data($contact);
    if (!$validation['success']) {
        wp_send_json_error(array('message' => $validation['message']), 400);
    }

    // Collect and validate items
    $items_result = $this->collect_items($_POST);
    if (!$items_result['success']) {
        wp_send_json_error(array('message' => $items_result['message']), 400);
    }

    $items = $items_result['items'];
    $total_estimated = $items_result['total'];

    // Prepare data for processing
    $form_data = array(
        'items'   => $items,
        'totals'  => array('estimated' => $total_estimated),
        'contact' => $contact,
    );

    

    // Send email to admin

    $email_sent = $this->send_admin_notification($form_data);

    if ($email_sent) {
        // Save to DB

        $save_id = $this->save_submission($form_data);


        // Send user confirmation
        $user_mail = $this->send_user_confirmation(
            $contact['email'],
            $contact['firstName'] . ' ' . $contact['lastName'],
            $total_estimated,
            $form_data
        );

        wp_send_json_success(array(
            'message' => 'Your gold sell form has been submitted successfully. We will contact you soon!'
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to send email. Please try again.'), 500);
    }
}

    /**
     * Sanitize contact data from POST
     * 
     * @param array $post_data POST data
     * @return array Sanitized contact data
     */
    private function sanitize_contact_data($post_data) {
        return array(
            'firstName'   => sanitize_text_field($post_data['firstName'] ?? ''),
            'lastName'    => sanitize_text_field($post_data['lastName'] ?? ''),
            'phone'       => sanitize_text_field($post_data['phone'] ?? ''),
            'email'       => sanitize_email($post_data['email'] ?? ''),
            'houseNumber' => sanitize_text_field($post_data['houseNumber'] ?? ''),
            'town'        => sanitize_text_field($post_data['town'] ?? ''),
            'apartment'   => sanitize_text_field($post_data['apartment'] ?? ''),
            'postcode'    => sanitize_text_field($post_data['postcode'] ?? ''),
            'description' => sanitize_textarea_field($post_data['description'] ?? ''),
        );
    }

    /**
     * Validate contact data
     * 
     * @param array $contact Contact data
     * @return array Validation result
     */
    private function validate_contact_data($contact) {
        if (empty($contact['firstName']) || empty($contact['lastName']) || empty($contact['email']) || empty($contact['phone'])) {
            return array(
                'success' => false,
                'message' => 'Please fill in all required fields'
            );
        }

        if (!is_email($contact['email'])) {
            return array(
                'success' => false,
                'message' => 'Please enter a valid email address'
            );
        }

        return array('success' => true);
    }

    /**
     * Collect and validate items from POST
     * 
     * @param array $post_data POST data
     * @return array Items collection result
     */
    private function collect_items($post_data) {
        $items = array();
        $total_estimated = 0;

        if (!isset($post_data['items']) || !is_array($post_data['items'])) {
            return array(
                'success' => false,
                'message' => 'Please add at least one item'
            );
        }

        foreach ($post_data['items'] as $item) {
            $single_item = array(
                'weight' => floatval($item['weight'] ?? 0),
                'metal'  => sanitize_text_field($item['metal'] ?? ''),
                'value'  => floatval($item['value'] ?? 0),
            );

            $items[] = $single_item;
            $total_estimated += $single_item['value'];
        }

        // if (empty($items)) {
        //     return array(
        //         'success' => false,
        //         'message' => 'Please add at least one item'
        //     );
        // }

        return array(
            'success' => true,
            'items' => $items,
            'total' => $total_estimated
        );
    }

    /**
     * Send Admin Notification Email
     * 
     * @param array $form_data Form data
     * @return bool True if email was sent successfully
     */
private function send_admin_notification($form_data) {
    $admin_email = 'Info@ts-bullion.co.uk';
    $site_name = get_bloginfo('name');
    $site_url = get_bloginfo('url');

    // Check if items array is not empty
    $has_items = !empty($form_data['items']);

    // Build table only if items exist
    $items_table = $has_items ? $this->build_items_table($form_data['items'], $form_data['totals']['estimated']) : '';
    $contact_info = $this->build_contact_info($form_data['contact']);

    $email_subject = 'New Form Submission from ' . esc_html($form_data['contact']['firstName'] . ' ' . $form_data['contact']['lastName']);

    // Only show valuation if items exist
    $valuation_section = $has_items ? '
        <div style="background-color: #fffbea; border-left: 4px solid #ffc107; padding: 15px; margin-top: 20px;">
            <p><strong>Submission Date:</strong> ' . current_time('mysql') . '</p>
            <p><strong>Total Items:</strong> ' . count($form_data['items']) . '</p>
            <p><strong>Total Value:</strong> £' . number_format($form_data['totals']['estimated'], 2) . '</p>
        </div>' : '';

    $email_body = '<html><body style="font-family: Arial, sans-serif; background-color: #f5f5f5;">
        <div style="background-color: #ffffff; max-width: 600px; margin: 20px auto; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <h2 style="color: #333; border-bottom: 3px solid #ffc107; padding-bottom: 10px;">New Precious Gold Sell Submission</h2>

            <p>You have received a form submission from your website <strong>T&S Bullion - Sell Your Gold For Cash</strong></p>

            ' . $contact_info . '

            ' . ($has_items ? '<h3>Items Submitted:</h3>' . $items_table : '') . '

            ' . $valuation_section . '

            <div style="border-top: 1px solid #ddd; margin-top: 30px; padding-top: 10px; font-size: 13px; color: #555; text-align: center;">
                <p style="margin: 0;"><strong>Address:</strong> T&S Bullion Unit 3, Old Stone Yard, Derker Street, Oldham, OL1 4BE</p>
                <p style="margin: 0;"><strong>Phone:</strong> <a href="tel:+441612416931" style="color: #0073aa; text-decoration: none;">0161 241 6931</a></p>
                <p style="margin: 0;"><strong>Website:</strong> <a href="' . esc_url($site_url) . '" style="color: #0073aa;" target="_blank">' . esc_html($site_url) . '</a></p>
            </div>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
            <p style="color: #666; font-size: 12px; text-align: center;">This is an automated email from ' . esc_html($site_name) . '. Please do not reply to this email.</p>
        </div>
    </body></html>';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . esc_html($site_name) . ' <' . esc_html($admin_email) . '>',
        'Reply-To: ' . esc_html($admin_email)
    );

    return wp_mail($admin_email, $email_subject, $email_body, $headers);
}


    /**
     * Send User Confirmation Email
     * 
     * @param string $contact_email User email
     * @param string $contact_name User name
     * @param float  $total_value Total estimated value
     * @return bool True if email was sent successfully
     */
private function send_user_confirmation($contact_email, $contact_name, $total_value, $form_data) {
    $site_name = get_bloginfo('name');
    $site_url  = get_bloginfo('url');
    $admin_email = 'Info@ts-bullion.co.uk';

    // Check if items array has data
    $has_items = !empty($form_data['items']);
    $items_table = $has_items ? $this->build_items_table($form_data['items'], $form_data['totals']['estimated']) : '';

    // Always build contact info
    $contact_info = $this->build_contact_info($form_data['contact']);

    $email_subject = 'Thank you for your submission to T&S Bullion - Sell Your Gold For Cash';

    $email_body = '<html><body style="font-family: Arial, sans-serif;">
        <div style="background-color: #ffffff; max-width: 600px; margin: 20px auto; padding: 20px; border-radius: 5px;">
            <p>Dear ' . esc_html($contact_name) . ',</p>
            <p>Thank you for sending your information.</p>

            <!-- Always show contact info -->
            ' . $contact_info . '

            <!-- Conditionally show items table and totals -->
            ' . ($has_items ? '<p>We have received your submission with a total estimated value of <strong>£' . number_format($total_value, 2) . '</strong>.</p>
            <h4>Items Submitted:</h4>' . $items_table : '') . '

            <div style="background-color: #fffbea; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                <p><strong>What happens next:</strong></p>
                <ul style="margin: 10px 0;">
                    <li>We will send out your secure & insured Royal Mail envelope</li>
                    <li>Place your precious metals in the envelope and post it out to us</li>
                    <li>Once we receive your item(s), our expert team will review them and contact you within 24 hours</li>
                    <li>We will provide you with a detailed quotation</li>
                    <li>We will then pay you directly as soon as you accept our valuation</li>
                </ul>
            </div>

            <p>If you have any questions in the meantime, please don\'t hesitate to contact us.</p>
            <p>Best regards,<br><strong>' . esc_html($site_name) . '</strong></p>

            <div style="border-top: 1px solid #ddd; margin-top: 30px; padding-top: 10px; font-size: 13px; color: #555; text-align: center;">
                <p style="margin: 0;"><strong>Address:</strong> T&S Bullion Unit 3, Old Stone Yard, Derker Street, Oldham, OL1 4BE</p>
                <p style="margin: 0;"><strong>Phone:</strong> <a href="tel:+441612416931" style="color: #0073aa; text-decoration: none;">0161 241 6931</a></p>
                <p style="margin: 0;"><strong>Website:</strong> <a href="' . esc_url($site_url) . '" style="color: #0073aa;" target="_blank">' . esc_html($site_url) . '</a></p>
            </div>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
            <p style="color: #666; font-size: 12px; text-align: center;">This is an automated email from ' . esc_html($site_name) . '. Please do not reply to this email.</p>
        </div>
    </body></html>';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . esc_html($site_name) . ' <' . esc_html($admin_email) . '>',
        'Reply-To: ' . esc_html($admin_email)
    );

    return wp_mail($contact_email, $email_subject, $email_body, $headers);
}

    /**
     * Build Items Table HTML
     * 
     * @param array $items Items array
     * @param float $total_value Total value
     * @return string HTML table
     */
    private function build_items_table($items, $total_value) {
        $items_table = '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr style="background-color: #f0f0f0; border: 1px solid #ddd;">
                    <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Weight (g)</th>
                    <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Metal Type</th>
                    <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Value</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($items as $item) {
            $items_table .= '<tr style="border: 1px solid #ddd;">
                <td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($item['weight']) . '</td>
                <td style="padding: 10px; border: 1px solid #ddd;">' . esc_html($item['metal']) . '</td>
                <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">£' . number_format($item['value'], 2) . '</td>
            </tr>';
        }

        $items_table .= '<tr style="background-color: #f0f0f0; font-weight: bold; border: 1px solid #ddd;">
            <td colspan="2" style="padding: 10px; border: 1px solid #ddd; text-align: right;">Total Estimated Value:</td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">£' . number_format($total_value, 2) . '</td>
        </tr>';

        $items_table .= '</tbody></table>';

        return $items_table;
    }

    /**
     * Build Contact Information Section
     * 
     * @param array $contact Contact data
     * @return string HTML contact info
     */
    private function build_contact_info($contact) {
        $contact_info = '<h3>Contact Information:</h3>
            <p><strong>Name:</strong> ' . esc_html($contact['firstName']) . ' ' . esc_html($contact['lastName']) . '</p>
            <p><strong>Email:</strong> ' . esc_html($contact['email']) . '</p>
            <p><strong>Phone:</strong> ' . esc_html($contact['phone']) . '</p>
            <p><strong>Address:</strong> ' . esc_html($contact['houseNumber']) . ' ' . esc_html($contact['apartment']) . ', ' . esc_html($contact['town']) . ' ' . esc_html($contact['postcode']) . '</p>';

        if (!empty($contact['description'])) {
            $contact_info .= '<p><strong>Additional Notes:</strong> ' . nl2br(esc_html($contact['description'])) . '</p>';
        }

        return $contact_info;
    }

    /**
     * Save Submission to Database
     * 
     * @param array $form_data Form data
     * @return int|false Insert ID or false on failure
     */
    private function save_submission($form_data) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'gold_sell_submissions';

        // Check if table exists, if not create it
        if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
            $this->create_submissions_table();
        }

        $contact = $form_data['contact'];

        $insert_data = array(
            'first_name'   => $contact['firstName'],
            'last_name'    => $contact['lastName'],
            'email'        => $contact['email'],
            'phone'        => $contact['phone'],
            'house_number' => $contact['houseNumber'],
            'apartment'    => $contact['apartment'],
            'town'         => $contact['town'],
            'postcode'     => $contact['postcode'],
            'description'  => $contact['description'],
            'total_value'  => $form_data['totals']['estimated'],
            'items_data'   => wp_json_encode($form_data['items']),
            'submitted_at' => current_time('mysql'),
        );

        $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s');

        $result = $wpdb->insert($table_name, $insert_data, $format);

        if ($result === false) {
            error_log('Gold Sell Submission Database Error: ' . $wpdb->last_error);
            return false;
        }

        return $wpdb->insert_id;
    }

    /**
     * Create Submissions Table
     * 
     * @return void
     */
    private function create_submissions_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'gold_sell_submissions';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            house_number VARCHAR(100),
            apartment VARCHAR(100),
            town VARCHAR(100) NOT NULL,
            postcode VARCHAR(20) NOT NULL,
            description LONGTEXT,
            total_value DECIMAL(10, 2),
            items_data LONGTEXT NOT NULL,
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            KEY email (email),
            KEY submitted_at (submitted_at)
        ) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
