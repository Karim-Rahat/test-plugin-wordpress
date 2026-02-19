<?php
/**
 * Admin Data Page for Gold Sell Submissions
 */

namespace GOLD_CALCULATOR\Inc\Classes;

if (!defined('ABSPATH')) {
    exit;
}

use GOLD_CALCULATOR\Inc\Traits\Singleton;

class Admin_datapage {
    use Singleton;

    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_page'));
    }

    public function register_admin_page() {
        add_menu_page(
            'Gold Sell Submissions',
            'Gold Submissions',
            'manage_options',
            'gold-sell-submissions',
            array($this, 'display_submissions_page'),
            'dashicons-admin-generic',
            25
        );
    }

    public function display_submissions_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'gold_sell_submissions';

        // Get sorting params from URL
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'submitted_at';
        $order   = isset($_GET['order']) ? strtoupper(sanitize_text_field($_GET['order'])) : 'DESC';
        if (!in_array($orderby, ['id', 'submitted_at'])) $orderby = 'submitted_at';
        if (!in_array($order, ['ASC', 'DESC'])) $order = 'DESC';

        // Toggle order for links
        $id_order        = ($orderby === 'id' && $order === 'ASC') ? 'DESC' : 'ASC';
        $date_order      = ($orderby === 'submitted_at' && $order === 'ASC') ? 'DESC' : 'ASC';

        $submissions = $wpdb->get_results("SELECT * FROM {$table_name} ORDER BY {$orderby} {$order}");

        echo '<div class="wrap">';
        echo '<h1>Gold Sell Submissions</h1>';

        if (empty($submissions)) {
            echo '<p>No submissions found.</p>';
        } else {
            echo '<table class="widefat fixed striped">';
            echo '<thead>
                    <tr>
                        <th><a href="' . esc_url(add_query_arg(['orderby'=>'id','order'=>$id_order])) . '">ID</a></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Items</th>
                        <th>Descriptions of items</th>
                        <th><a href="' . esc_url(add_query_arg(['orderby'=>'submitted_at','order'=>$date_order])) . '">Submitted At</a></th>
                    </tr>
                  </thead><tbody>';

            foreach ($submissions as $submission) {
                $items = json_decode($submission->items_data, true);
                $items_html = '<table style="width:100%; border-collapse: collapse;">';
                $items_html .= '<tr><th style="border:1px solid #ddd; padding:5px;">Metal</th><th style="border:1px solid #ddd; padding:5px;">Weight (g)</th><th style="border:1px solid #ddd; padding:5px;">Value</th></tr>';

                if (is_array($items)) {
                    foreach ($items as $item) {
                        $items_html .= '<tr>
                            <td style="border:1px solid #ddd; padding:5px;">' . esc_html($item['metal']) . '</td>
                            <td style="border:1px solid #ddd; padding:5px;">' . esc_html($item['weight']) . '</td>
                            <td style="border:1px solid #ddd; padding:5px;">$' . number_format($item['value'], 2) . '</td>
                        </tr>';
                    }
                }


                $items_html .= '</table>';

                echo '<tr>
                        <td>' . esc_html($submission->id) . '</td>
                        <td>' . esc_html($submission->first_name . ' ' . $submission->last_name) . '</td>
                        <td>' . esc_html($submission->email) . '</td>
                        <td>' . esc_html($submission->phone) . '</td>
                        <td>' . $items_html . '</td>
                        <td>' . esc_html($submission->description) . '</td>
                        <td>' . esc_html($submission->submitted_at) . '</td>
                    </tr>';
            }

            echo '</tbody></table>';
        }

        echo '</div>';
    }
}