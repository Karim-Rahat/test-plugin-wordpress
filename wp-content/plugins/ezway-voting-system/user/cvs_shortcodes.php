<?php

function cvs_display_contest($attrs)
{
    $if_elementor = isset($_GET['elementor-preview']) ? $_GET['elementor-preview'] : false;
    if ($if_elementor) {
        return;
    }

    $contest_id = $attrs["id"] ?? '';

    if (empty($contest_id)) {
        return '<div class="alert alert-danger">Contest ID is required</div>';
    }

    global $wpdb;

    $contestant_table = $wpdb->prefix . 'cvs_contestants';
    $categories       = $wpdb->get_col("SELECT DISTINCT category FROM $contestant_table WHERE contest_id = $contest_id");

    ob_start();

    include_once $GLOBALS['cvs_directory_root_path'] . 'user/html/cvs-contest-page.php';

    return ob_get_clean();
}

add_shortcode("cvs_contest", "cvs_display_contest");

function cvs_display_leaderboard($attrs)
{
    $if_elementor = isset($_GET['elementor-preview']) ? $_GET['elementor-preview'] : false;
    if ($if_elementor) {
        return;
    }

    $contest_id = $attrs["id"] ?? '';

    if (empty($contest_id)) {
        return '<div class="alert alert-danger">Contest ID is required</div>';
    }

    $contestant_array = cvs_get_contestant_array($contest_id, 'top');

    ob_start();

    include_once $GLOBALS['cvs_directory_root_path'] . 'user/html/cvs-leaderboard-page.php';

    return ob_get_clean();
}

add_shortcode('cvs_leaderboard', 'cvs_display_leaderboard');

function cvs_display_nominee_submisison_form($attrs)
{
    $if_elementor = isset($_GET['elementor-preview']) ? $_GET['elementor-preview'] : false;
    if ($if_elementor) {
        return;
    }

    global $wpdb;

    $contest_table = $wpdb->prefix . 'cvs_contests';
    $categories    = array_unique(
        array_merge(
            ...array_map(
                fn($category) => explode(',', $category), $wpdb->get_col("SELECT categories FROM $contest_table")
            )
        )
    );

    $style = shortcode_atts([
        'margin' => '40px 0',
    ], $attrs);

    $style = array_map(fn($key, $val) => sprintf('%s:%s', $key, $val), array_keys($style), array_values($style));
    $style = join(' ', $style);

    ob_start();

    include_once $GLOBALS['cvs_directory_root_path'] . 'user/html/cvs-nominee-submission-form.php';

    return ob_get_clean();
}

add_shortcode("cvs_nominee_submission", "cvs_display_nominee_submisison_form");

// enqueue public assets
function cvs_load_public_assets()
{
    wp_enqueue_style("cvs_voting_style", cvs_plugin_dir_url . "/assets/css/user-style.css");
    wp_enqueue_style("fontawesome", 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');

    wp_enqueue_script("cvs_voting_script", cvs_plugin_dir_url . "/assets/js/user-script.js", ['jquery']);

    // Localize the script with new data
    $cvs_settings = [
        "ajax_url"               => admin_url('admin-ajax.php'),
        "cvs_nonce"              => wp_create_nonce("cvs_ajax_validation"),
        "paypal_cliend_ID"       => $GLOBALS["cvs_settings"]->paypal_api ?? '',
        "stripe_publishable_KEY" => $GLOBALS["cvs_settings"]->stripe_pk ?? '',
        "paypal_active"          => 'on' === ($GLOBALS["cvs_settings"]->paypal_active ?? ''),
        "stripe_active"          => 'on' === ($GLOBALS["cvs_settings"]->stripe_active ?? ''),
    ];

    wp_localize_script("cvs_voting_script", "cvs_settings", $cvs_settings);
}

add_action('wp_enqueue_scripts', 'cvs_load_public_assets');

// ajax for providing contestant data
function cvs_get_contestant_data()
{
    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'cvs_ajax_validation')) {
        wp_send_json_error('Invalid nonce');
        exit;
    }

    $contest_id = intval($_POST['contest_id'] ?? 0);
    $sort_by    = sanitize_text_field($_POST['sort_by'] ?? 'top');
    $filter_by  = sanitize_text_field($_POST['filter_by'] ?? 'all');

    $contestant_array = cvs_get_contestant_array($contest_id, $sort_by, $filter_by);

    return wp_send_json($contestant_array);
}

add_action('wp_ajax_cvs_get_contestant_data', 'cvs_get_contestant_data');
add_action('wp_ajax_nopriv_cvs_get_contestant_data', 'cvs_get_contestant_data');

function cvs_get_contestant_array($contest_id, $sort_by, $filter_by = 'all')
{
    global $wpdb;

    $contest_table    = $wpdb->prefix . 'cvs_contests';
    $contestant_table = $wpdb->prefix . 'cvs_contestants';
    $contest_data     = $wpdb->get_results("SELECT * FROM $contest_table")[0];
    $contestant_data  = $wpdb->get_results("SELECT * FROM $contestant_table WHERE contest_id = $contest_id " . ($filter_by !== 'all' ? " AND category LIKE '%{$filter_by}%' " : '') . " ORDER BY " . (['az_asc' => 'name ASC', 'az_desc' => 'name DESC'][$sort_by] ?? 'id DESC'));
    $voter_table      = $wpdb->prefix . "cvs_voters";
    $contestant_array = [];

    foreach ($contestant_data as $d) {
        $contest_id         = $d->contest_id;
        $contestant_id      = $d->id;
        $total_vote         = $wpdb->get_results("SELECT SUM(number_of_vote) AS total FROM $voter_table WHERE contest_id=$contest_id && contestant_id=$contestant_id && status=true;")[0]->total;
        $contestant_array[] = [
            "id"          => $d->id,
            'contest_id'  => $d->contest_id,
            "name"        => $d->name,
            "image"       => $d->image,
            "category"    => $d->category,
            "description" => $d->description,
            "vote"        => $total_vote,
        ];
    }

    if ($sort_by === 'top') {
        for ($i = 0; $i < count($contestant_array); $i++) {
            for ($j = 0; $j < count($contestant_array) - 1; $j++) {
                if ($contestant_array[$j]["vote"] < $contestant_array[$j + 1]["vote"]) {
                    $temp                     = $contestant_array[$j];
                    $contestant_array[$j]     = $contestant_array[$j + 1];
                    $contestant_array[$j + 1] = $temp;
                }
            }
        }
    }

    return $contestant_array;
}

function cvs_submit_vote_function()
{
    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'cvs_ajax_validation')) {
        wp_send_json_error('Invalid nonce');
        exit;
    }

    global $wpdb;

    $payment_method = $_POST['paymewnt_method'] ?? '';

    if (! in_array($payment_method, ['paypal', 'stripe'])) {
        wp_send_json(['success' => false, 'message' => 'Invalid Payment Method, Please try again.']);
    }

    $payment_result = $_POST['payment_result'] ?? [];

    if (empty($payment_result)) {
        wp_send_json(['success' => false, 'message' => 'Invalid Payment Information, Please try again.']);
    }

    $payment_amount   = intval($_POST['total_vote'] ?? 1);
    $payment_status   = 'pending';
    $payment_currency = 'USD';
    $payment_id       = '';
    $billing_details  = [
        'street'  => $_POST['street'] ?? null,
        'city'    => $_POST['city'] ?? null,
        'state'   => $_POST['state'] ?? null,
        'zip'     => $_POST['zip'] ?? null,
        'country' => $_POST['country'] ?? null,
    ];

    if ($payment_method === 'paypal' && isset($payment_result['id']) && ! empty($payment_result['id'])) {
        $payment_status = ('capture' === strtolower($payment_result['intent']) && 'completed' === strtolower($payment_result['status'])) ? 'paid' : 'failed';

        $payment_id = $payment_result['id'];

        $shipping_address = $payment_result['purchase_units'][0]['shipping']['address'] ?? [];

        // fill empty billing fields
        $billing_details['street'] ??= ($shipping_address['address_line_1'] ?? '');
        $billing_details['state'] ??= ($shipping_address['admin_area_1'] ?? '');
        $billing_details['city'] ??= ($shipping_address['admin_area_2'] ?? '');
        $billing_details['zip'] ??= ($shipping_address['postal_code'] ?? '');
        $billing_details['country'] ??= ($shipping_address['country_code'] ?? '');
    } elseif ($payment_method === 'stripe' && isset($payment_result['id']) && ! empty($payment_result['id'])) {
        $payment_status = ('payment_intent' === strtolower($payment_result['object']) && 'succeeded' === strtolower($payment_result['status'])) ? 'paid' : 'failed';

        $payment_id = $payment_result['id'];
    } else {
        wp_send_json(['success' => false, 'message' => 'Failed to validate payment, Please try again later.']);
    }

    foreach ($billing_details as $k => $v) {
        $billing_details[$k] = $v ?? '';
    }

    $vote_data = array_merge($billing_details, [
        'contest_id'     => intval($_POST['contest_id'] ?? 0),
        'contestant_id'  => intval($_POST['contestant_id'] ?? 0),
        'name'           => sanitize_text_field($_POST['fullname'] ?? ''),
        'email'          => sanitize_email($_POST['email'] ?? ''),
        'phone'          => sanitize_text_field($_POST['phone'] ?? ''),
        'number_of_vote' => intval($_POST['total_vote'] ?? 1),
        'status'         => 'paid' === $payment_status,
    ]);

    $voter_table    = $wpdb->prefix . 'cvs_voters';
    $payments_table = $wpdb->prefix . 'cvs_payments';

    $wpdb->insert($voter_table, $vote_data);

    if ($wpdb->insert_id) {

        // payments details
        $payment_data = [
            'contest_id'    => intval($_POST['contest_id'] ?? 0),
            'contestant_id' => intval($_POST['contestant_id'] ?? 0),
            'voter_id'      => $wpdb->insert_id,
            'amount'        => $payment_amount,
            'currency'      => $payment_currency,
            'payment_id'    => $payment_id,
            'method'        => $payment_method,
            'status'        => $payment_status,
            'created_at'    => gmdate('Y-m-d H:i:s'),
        ];

        $wpdb->insert($payments_table, $payment_data);

        if ('paid' === $payment_status) {
            wp_send_json(['success' => true, 'message' => 'Vote has been submitted']);
        }

        wp_send_json(['success' => false, 'payment_failed' => true, 'message' => 'Payment failed, Please try again later.']);
    }

    wp_send_json(['success' => false, 'message' => 'Failed to submit the vote.']);
}

add_action('wp_ajax_cvs_submit_vote', 'cvs_submit_vote_function');
add_action('wp_ajax_nopriv_cvs_submit_vote', 'cvs_submit_vote_function');

function cvs_stripe_create_payment_intend_function()
{
    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'cvs_ajax_validation')) {
        wp_send_json_error('Invalid nonce');
        exit;
    }

    $stripeSecretKey = $GLOBALS["cvs_settings"]->stripe_sk ?? '';

    $amount = isset($_POST['amount']) ? (int) sanitize_text_field($_POST['amount']) : 0;

    $request = wp_remote_post('https://api.stripe.com/v1/payment_intents', [
        'method'  => 'POST',
        'body'    => http_build_query([
            'amount'   => $amount * 100, // in cents, $1 = 100 cents
            'currency' => 'usd',
        ]),
        'headers' => [
            'authorization' => "Bearer $stripeSecretKey",
            'content-type'  => 'application/x-www-form-urlencoded',
        ],
    ]);

    if (is_wp_error($request)) {
        wp_send_json(['success' => false, 'message' => 'Failed to connect stripe api, please try again later.']);
    }

    $body = wp_remote_retrieve_body($request);

    $to_json = json_decode($body, true);
    if (! isset($to_json['id'], $to_json['client_secret'])) {
        wp_send_json(['success' => false, 'message' => 'Failed to create payment, please try again later.']);
    }

    wp_send_json(['success' => true, 'body' => $to_json]);
}

add_action('wp_ajax_cvs_stripe_payment_intend', 'cvs_stripe_create_payment_intend_function');
add_action('wp_ajax_nopriv_cvs_stripe_payment_intend', 'cvs_stripe_create_payment_intend_function');

function cvs_nomination_form_submission_function()
{

    if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'cvs_ajax_validation')) {
        wp_send_json_error('Invalid nonce');
        exit;
    }

    if (empty($_FILES['image']['name'] ?? '')) {
        wp_send_json(['success' => false, 'message' => 'Nominee image is not set.']);
    } elseif (empty($_POST['name'] ?? '') || empty($_POST['email'] ?? '') || empty($_POST['phone'] ?? '')) {
        wp_send_json(['success' => false, 'message' => 'Please fill all the required fields.']);
    }

    // it allows us to use wp_handle_upload() function
    require_once ABSPATH . 'wp-admin/includes/file.php';

    $upload = wp_handle_upload($_FILES['image'], ['test_form' => false]);

    if (! empty($upload['error'])) {
        wp_send_json(['success' => false, 'message' => 'Error: ' . $upload['error']]);
    }

    // it is time to add our uploaded image into WordPress media library
    $attachment_id = wp_insert_attachment(
        [
            'guid'           => $upload['url'],
            'post_mime_type' => $upload['type'],
            'post_title'     => basename($upload['file']),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ],
        $upload['file']
    );

    if (is_wp_error($attachment_id) || ! $attachment_id) {
        wp_send_json(['success' => false, 'message' => 'Failed upload nominee image, Please try again later.']);
    }

    // update medatata, regenerate image sizes
    require_once ABSPATH . 'wp-admin/includes/image.php';

    wp_update_attachment_metadata(
        $attachment_id,
        wp_generate_attachment_metadata($attachment_id, $upload['file'])
    );

    $data = [
        'name'       => sanitize_text_field($_POST['name'] ?? ''),
        'email'      => sanitize_text_field($_POST['email'] ?? ''),
        'image'      => $attachment_id,
        'phone'      => sanitize_text_field($_POST['phone'] ?? ''),
        'category'   => sanitize_text_field($_POST['category'] ?? ''),
        'created_at' => gmdate('Y-m-d H:i:s'),
    ];

    global $wpdb;

    $nominee_table = $wpdb->prefix . 'cvs_nominees';

    $wpdb->insert($nominee_table, $data);

    if ($wpdb->insert_id) {
        wp_send_json(['success' => true, 'message' => 'Nominee record has been sotred successfully.']);
    }

    // delete the nominee image if uploaded
    wp_delete_attachment($attachment_id);

    wp_send_json(['success' => false, 'message' => 'Failed to storee nominee record, Please try again later.']);
}

add_action('wp_ajax_cvs_submit_nomination', 'cvs_nomination_form_submission_function');
add_action('wp_ajax_nopriv_cvs_submit_nomination', 'cvs_nomination_form_submission_function');