<?php

    global $wpdb;

    $current_url = home_url() . $_SERVER["REQUEST_URI"];
    $page_url    = explode("p=", $current_url)[0];

    $nominee_table = $wpdb->prefix . 'cvs_nominees';

    if (isset($_GET['nominee_delete_id'], $_GET['auth_key']) && $_GET["auth_key"] === md5(base64_encode(wp_get_current_user()->user_pass))) {

        $nominee_id    = $_GET['nominee_delete_id'] ?? 0;
        $attachment_id = $wpdb->get_var("SELECT image FROM {$nominee_table} WHERE id = {$nominee_id}");

        if ($attachment_id) {
            wp_delete_attachment($attachment_id);
        }

        $wpdb->delete($nominee_table, ['id' => $nominee_id]);

        cvs_window_redirect(remove_query_arg(['nominee_delete_id', 'auth_key']));
    }

    $total_nominees_rows = $wpdb->get_var("SELECT COUNT(1) FROM {$nominee_table}");

    $per_page = (int) $GLOBALS["cvs_settings"]->voter_pagination;
    $page     = 1;
    $start    = 0;
    $end      = $start + $per_page;

    if (isset($_GET["p"])) {
        $start = ($_GET["p"] - 1) * $per_page;
        $page  = $_GET["p"];
    }

    $nominees_data = $wpdb->get_results("SELECT * FROM {$nominee_table} ORDER BY id DESC LIMIT $start, $end;", ARRAY_A);

?>
<div class="container mt-5">
    <div class="jumbotron">
        <h1>Nominee Submissions</h1>
        <a id="contest_edit_btn" href="<?php echo add_query_arg('__cvs_export_all_nominee', 'csv') ?>"
            class="btn btn-success mt-3">Export All in CSV</a>
    </div>
    <table class="table table-bordered">
        <thead class="text-center">
            <tr>
                <th></th>
                <th>Nominee</th>
                <th>Category</th>
                <th>Submitted at</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($nominees_data as $nominee): ?>
            <tr>
                <td>
                    <img src="<?php echo wp_get_attachment_url($nominee['image']) ?>" class="img-thumbnail" width="100"
                        height="100">
                </td>
                <td>
                    <span class="text-muted">Name:</span> <?php echo $nominee['name'] ?? '' ?> <br>
                    <span class="text-muted">Email:</span> <?php echo $nominee['email'] ?? '' ?> <br>
                    <span class="text-muted">Phone:</span> <?php echo $nominee['phone'] ?? '' ?>
                </td>
                <td><?php echo $nominee['category'] ?></td>
                <td><?php echo gmdate('d M, Y g:i A', strtotime($nominee['created_at'])) ?></td>
                <td class="text-center">
                    <a type="button" class="btn btn-danger contestant_delete_btn" data-action="nominee"
                        href="<?php echo $page_url . '&nominee_delete_id=' . $nominee['id'] . '&auth_key=' . md5(base64_encode(wp_get_current_user()->user_pass)); ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach?>
            <?php if (empty($nominees_data)): ?>
            <tr>
                <td colspan="5" class="text-center p-3">
                    <p class="m-0">No submissions yet</p>
                </td>
            </tr>
            <?php endif?>
        </tbody>
    </table>
    <br>
    <nav>
        <ul class="pagination justify-content-cente">
            <?php
                $pages = ceil($total_nominees_rows / $per_page);
                $page  = 1;

                if (isset($_GET["p"])) {
                    $page = $_GET["p"];
                }

                if ($page <= 1) {
                    echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                } else {
                    echo '<li class="page-item"><a class="page-link" href="' . add_query_arg('p', $page - 1) . '">Previous</a></li>';
                }

                for ($i = 1; $i <= $pages; $i++) {
                    if ($page == $i) {
                        $status = "active";
                    } else {
                        $status = "";
                    }
                    echo '<li class="page-item ' . $status . '"><a class="page-link" href="' . add_query_arg('p', $i) . '">' . $i . '</a></li>';
                }

                if ($page == (int) $pages) {
                    echo '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
                } else {
                    echo '<li class="page-item"><a class="page-link" href="' . add_query_arg('p', $page + 1) . '">Next</a></li>';
                }
            ?>
        </ul>
    </nav>
</div>