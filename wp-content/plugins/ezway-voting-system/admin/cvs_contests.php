<?php
    global $wpdb;

    $current_url           = home_url() . $_SERVER["REQUEST_URI"];
    $page_url              = explode("&", $current_url)[0];
    $voter_table_name      = $wpdb->prefix . "cvs_voters";
    $contest_table_name    = $wpdb->prefix . 'cvs_contests';
    $contestant_table_name = $wpdb->prefix . 'cvs_contestants';
    $voter_payments        = $wpdb->prefix . 'cvs_payments';

    if (isset($_GET["cvs_action"])) {
        $action = $_GET["cvs_action"];

        // render individual contest page
        if ($action === "individual_contest") {
            $contest_id = $_GET["contest_id"] ?? 0;

            // update contest
            if (isset($_POST["update_contest"]) && isset($_POST["contest_name"]) && isset($_POST["contest_description"])) {
                $contest_name        = $_POST["contest_name"] ?? '';
                $contest_categories  = $_POST["contest_categories"] ?? '';
                $contest_description = $_POST["contest_description"] ?? '';

                if ($contest_name != "" && $contest_description != "") {
                    $wpdb->query("UPDATE $contest_table_name SET name = '$contest_name', categories = '$contest_categories', description = '$contest_description' WHERE id = $contest_id");
                }
            }

            // create contestant
            if (isset($_POST["contestant_name"]) && isset($_POST["contestant_image"]) && isset($_POST["contestant_description"])) {
                $contestant_name     = $_POST["contestant_name"] ?? '';
                $contestant_image    = $_POST["contestant_image"] ?? '';
                $contestant_category = $_POST["contestant_category"] ?? '';
                $contestant_details  = $_POST["contestant_description"] ?? '';

                $query = "INSERT INTO $contestant_table_name (contest_id, name, category, image, description) VALUES  ('$contest_id', '$contestant_name', '$contestant_category', '$contestant_image', '$contestant_details')";
                if ($contestant_name != "" && $contestant_image != "" && $contestant_details != "") {
                    $wpdb->query($query);
                }
            }

            // Delete contestant
            if (isset($_GET["contestant_delete_id"], $_GET["auth_key"]) && $_GET["auth_key"] == md5(base64_encode(wp_get_current_user()->user_pass))) {
                $cd_id = $_GET["contestant_delete_id"];

                $wpdb->query("DELETE FROM $contestant_table_name WHERE id = $cd_id");
                $wpdb->query("DELETE FROM $voter_table_name WHERE contestant_id = $cd_id");
                $wpdb->query("DELETE FROM $voter_payments WHERE contestant_id = $cd_id");

                cvs_window_redirect(remove_query_arg(['contestant_delete_id', 'auth_key']));
            }

            // Display contest details
            $contest_data    = $wpdb->get_results("SELECT * FROM $contest_table_name WHERE id = $contest_id")[0];
            $contestant_data = $wpdb->get_results("SELECT * FROM $contestant_table_name WHERE contest_id = $contest_id");

            $arr_category = explode(',', $contest_data->categories ?? '');

        ?>
<div class="container mt-5">
    <div class="jumbotron">
        <h1><?php echo $contest_data->name ?></h1>
        <a type="button" class="btn btn-primary float-right mt-3" href=<?php echo $page_url ?>>Back</a>
        <a id="contest_edit_btn" type="button" class="btn btn-danger mt-3">Edit</a>
        <div id="contest_edit_area" class="mt-2">
            <form method="post">
                <div class="form-group">
                    <label for="contest_name">Contest Name</label>
                    <input value="<?php echo $contest_data->name ?>" name="contest_name" type="text"
                        class="form-control" id="contest_name" placeholder="Enter contest name">
                    <input type="hidden" name="update_contest" value="true">
                </div>
                <div class="form-group">
                    <label for="contest_categories">Categories (Comma Separated, If you edit category you have to update
                        it contestent again)</label>
                    <input value="<?php echo $contest_data->categories ?>" name="contest_categories" type="text"
                        class="form-control" id="contest_categories" placeholder="Enter categories name">
                    <input type="hidden" name="update_contest" value="true">
                </div>
                <div class="form-group">
                    <label for="contest_description">Contest Details</label>
                    <?php
                        $settings = [
                                    'textarea_name' => 'contest_description',
                                    'media_buttons' => false,
                                    'tinymce'       => [
                                        'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
                                        'bullist,blockquote,|,justifyleft,justifycenter' .
                                        ',justifyright,justifyfull,|,link,unlink,|' .
                                        ',spellchecker,wp_fullscreen,wp_adv',
                                    ],
                                ];
                                wp_editor($contest_data->description, "contest_editor", $settings);
                            ?>
                </div>
                <button type="submit" class="btn btn-danger">Update</button>
            </form>
        </div>
    </div>
    <?php echo $contest_data->description; ?>
    <br>
    <hr><br>
    <h4>Total contestant: <?php echo count($contestant_data); ?></h4>
    <!--            contestant creating area start-->
    <button id="contestant_add_btn" class="btn btn-success btn-block">+ Add new contestant</button>
    <div id="contestant_add_area">
        <img id="contestant_display_image" width="100" height="100" class="img-thumbnail m-2">
        <button id="contestant_image_btn" data-input-field="#contestant_image"
            data-display-field="#contestant_display_image" class="cvs_upload_media btn btn-success">Choose
            image</button>
        <form method="post">
            <div class="form-group">
                <label for="contestant_name">Full Name <span style="color:red">*</span></label>
                <input name="contestant_name" type="text" class="form-control" id="contestant_name"
                    placeholder="Enter Full Name">
                <input name="contestant_image" type="hidden" id="contestant_image">
            </div>
            <div class="form-group">
                <label for="contestant_name">Select Category <span style="color:red">*</span></label>

                <select name="contestant_category" class="form-control" id="contestant_category"
                    placeholder="Select Category">
                    <?php
                        foreach ($arr_category as $category) {
                                    echo '<option value="' . trim($category) . '">' . $category . '</option>';
                                }
                            ?>
                </select>
            </div>
            <div class="form-group">
                <label for="contestant_description">Contestant Details <span style="color:red">*</span></label>
                <?php
                    $settings = [
                                'textarea_name' => 'contestant_description',
                                'media_buttons' => false,
                                'tinymce'       => [
                                    'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
                                    'bullist,blockquote,|,justifyleft,justifycenter' .
                                    ',justifyright,justifyfull,|,link,unlink,|' .
                                    ',spellchecker,wp_fullscreen,wp_adv',
                                ],
                            ];
                            wp_editor("", "contestant_editor", $settings);
                        ?>
            </div>
            <button type="submit" class="btn btn-success">Add</button>
        </form>
    </div>
    <!--            contestant creating area end-->
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Contestant Name</th>
                <th>Category</th>
                <th>Vote</th>
                <th colspan="2" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // TODO: Try to Fix this N+1 query
                        foreach ($contestant_data as $d) {
                            $contest_id    = $d->contest_id;
                            $contestant_id = $d->id;
                            $total_vote    = $wpdb->get_results("SELECT SUM(number_of_vote) AS total FROM $voter_table_name WHERE contestant_id=$contestant_id && status=true;")[0]->total;
                        ?>
            <tr>
                <td><img width="100" height="100" class="img-thumbnail" src=<?php echo $d->image ?> /></td>
                <td><?php echo $d->name ?></td>
                <td><?php echo $d->category ?></td>
                <td><?php echo $total_vote ?? '<span class="text-muted small">N/A</span>' ?></td>
                <td class="text-center">
                    <a type="button" class="btn btn-primary"
                        href="<?php echo $page_url . '&cvs_action=individual_contestant&contest_id=' . $contest_id . '&contestant_id=' . $d->id ?>">Edit</a>
                </td>
                <td class="text-center">
                    <a type="button" class="btn btn-danger contestant_delete_btn"
                        href="<?php echo $page_url . '&cvs_action=individual_contest&contest_id=' . $contest_id . '&contestant_delete_id=' . $d->id . '&auth_key=' . md5(base64_encode(wp_get_current_user()->user_pass)); ?>">Delete</a>
                </td>
            </tr>
            <?php }?>

            <?php if (empty($contestant_data)): ?>
            <tr>
                <td colspan="6" class="text-center p-4">
                    <p>No Contestant Created yet.</p>
                    <p class="mb-0 text-muted">Please Click <u>+Add new</u> button above to create a new one.</p>
                </td>
            </tr>
            <?php endif?>
        </tbody>
    </table>
</div>
<?php }

        // render individual contestant page
        if ($action === "individual_contestant") {
            $contestant_id = $_GET["contestant_id"] ?? 0;
            $contest_id    = $_GET["contest_id"] ?? 0;

            // update contestant
            if (isset($_POST["contestant_name"]) && isset($_POST["contestant_image"]) && isset($_POST["contestant_description"])) {
                $contestant_name     = $_POST["contestant_name"] ?? '';
                $contestant_category = $_POST["contestant_category"] ?? '';
                $contestant_image    = $_POST["contestant_image"] ?? '';
                $contestant_details  = $_POST["contestant_description"] ?? '';

                if ($contestant_name != "" && $contestant_image != "" && $contestant_details != "") {
                    $query = "UPDATE $contestant_table_name SET name = '$contestant_name', category = '$contestant_category', image = '$contestant_image', description = '$contestant_details' WHERE id = $contestant_id";
                    $wpdb->query($query);
                }
            }

            // Display contest details
            $contestant_data = $wpdb->get_results("SELECT * FROM $contestant_table_name WHERE id = $contestant_id")[0];
            $contest_data    = $wpdb->get_results("SELECT * FROM $contest_table_name WHERE id = $contest_id")[0];

            $arr_category = explode(',', $contest_data->categories ?? '');
        ?>
<div class="container">
    <div class="jumbotron">
        <h1><?php echo $contestant_data->name; ?></h1>
        <a class="btn btn-primary float-right"
            href=<?php echo $page_url . "&cvs_action=individual_contest&contest_id=" . $contest_id ?>>Back</a>
    </div>
    <div>
        <img src="<?php echo $contestant_data->image ?>" id="contestant_display_image" width="100" height="100"
            class="img-thumbnail m-2">
        <button id="contestant_image_btn" data-input-field="#contestant_image"
            data-display-field="#contestant_display_image" class="cvs_upload_media btn btn-success">Choose
            image</button>
        <form method="post">
            <div class="form-group">
                <label for="contestant_name">Full Name <span style="color:red">*</span></label>
                <input value="<?php echo $contestant_data->name ?>" name="contestant_name" type="text"
                    class="form-control" id="contestant_name" placeholder="Enter Full Name">
                <input value="<?php echo $contestant_data->image ?>" name="contestant_image" type="hidden"
                    id="contestant_image">
            </div>
            <div class="form-group">
                <label for="contestant_name">Select Category <span style="color:red">*</span></label>

                <select value="<?php echo $contestant_data->category ?>" name="contestant_category" type="text"
                    class="form-control" id="contestant_category" placeholder="Select Category">
                    <?php
                        foreach ($arr_category as $category) {
                                    if ($category == $contestant_data->category) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }

                                    echo '<option value="' . trim($category) . '" ' . $selected . '>' . $category . '</option>';
                                }
                            ?>
                </select>
            </div>
            <div class="form-group">
                <label for="contestant_description">Contestant Details <span style="color:red">*</span></label>
                <?php
                    $settings = [
                                'textarea_name' => 'contestant_description',
                                'media_buttons' => false,
                                'tinymce'       => [
                                    'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
                                    'bullist,blockquote,|,justifyleft,justifycenter' .
                                    ',justifyright,justifyfull,|,link,unlink,|' .
                                    ',spellchecker,wp_fullscreen,wp_adv',
                                ],
                            ];
                            wp_editor($contestant_data->description, "editor", $settings);
                        ?>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>
<?php }

} else {?>
<!--    render contest page-->
<div class="container mt-5">
    <div class="jumbotron">
        <h1>Contests</h1>
    </div>
    <table class="table table-bordered">
        <thead class="text-center">
            <tr>
                <th>Shortcode</th>
                <th>Contest Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                //delete contest
                    if (isset($_GET["contest_delete_id"], $_GET["auth_key"]) && $_GET["auth_key"] == md5(base64_encode(wp_get_current_user()->user_pass))) {
                        $contest_id              = $_GET["contest_delete_id"];
                        $contest_delete_query    = "DELETE FROM $contest_table_name WHERE id = $contest_id";
                        $contestant_delete_query = "DELETE FROM $contestant_table_name WHERE contest_id = $contest_id";
                        $voter_delete_query      = "DELETE FROM $voter_table_name WHERE contest_id = $contest_id";
                        $voter_delete_query      = "DELETE FROM $voter_payments WHERE contest_id = $contest_id";

                        $wpdb->query($contest_delete_query);
                        $wpdb->query($contestant_delete_query);
                        $wpdb->query($voter_delete_query);

                        cvs_window_redirect(remove_query_arg(['contest_delete_id', 'auth_key']));
                    }
                    $contest_data = $wpdb->get_results("SELECT * FROM $contest_table_name");
                foreach ($contest_data as $d) {?>
            <tr>
                <th>[cvs_contest id=<?php echo $d->id ?>/]</th>
                <td><?php echo $d->name ?></td>
                <td class="text-center">
                    <a type="button" class="btn btn-primary"
                        href="<?php echo $page_url . '&cvs_action=individual_contest&contest_id=' . $d->id ?>">Open</a>
                </td>
                <td class="text-center">
                    <a type="button" class="btn btn-danger contest_delete_btn"
                        href="<?php echo $page_url . '&contest_delete_id=' . $d->id . '&auth_key=' . md5(base64_encode(wp_get_current_user()->user_pass)); ?>">Delete</a>
                </td>
            </tr>
            <?php }?>
            <?php if (empty($contest_data)): ?>
            <tr>
                <td colspan="6" class="text-center p-4">
                    <p>No Contest Created yet.</p>
                    <p class="mb-0 text-muted">Please <a
                            href="<?php echo esc_url(add_query_arg('page', 'cvsnewcontest')) ?>">Click Here</a> to
                        create a new one.</p>
                </td>
            </tr>
            <?php endif?>
        </tbody>
    </table>
</div>
<?php }?>