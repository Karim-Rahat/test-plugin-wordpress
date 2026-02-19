<?php
    global $wpdb;

    $table_name = $wpdb->prefix . 'cvs_contests';

    if (isset($_POST["contest_name"]) && isset($_POST["contest_description"])) {
        $contest_name        = $_POST["contest_name"] ?? '';
        $contest_categories  = $_POST["contest_categories"] ?? '';
        $contest_description = $_POST["contest_description"] ?? '';

        if ($contest_name != "" && $contest_description != "") {
            $wpdb->query("INSERT INTO $table_name (name, categories, description) VALUES ('$contest_name', '$contest_categories', '$contest_description')");

            cvs_window_redirect(add_query_arg(['page' => 'contestvotingsystem']));
        }
    }
?>
<div class="container mt-5">
    <div class="jumbotron">
        <h1>Create Contest</h1>
    </div>
    <form method="post">
        <div class="form-group">
            <label for="contest_name">Contest Name</label>
            <input name="contest_name" type="text" class="form-control" id="contest_name"
                placeholder="Enter contest name">
        </div>
        <div class="form-group">
            <label for="contest_categories">Contest Categories</label>
            <input name="contest_categories" type="text" class="form-control" id="contest_categories"
                placeholder="Enter Categories (Comma Separated)">
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
                wp_editor("", "editor", $settings);
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>