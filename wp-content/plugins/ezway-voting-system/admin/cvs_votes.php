<?php
    global $wpdb;

    $current_url = home_url() . $_SERVER["REQUEST_URI"];
    $page_url    = explode("p=", $current_url)[0];

    $contest_table    = $wpdb->prefix . 'cvs_contests';
    $voters_table     = $wpdb->prefix . 'cvs_voters';
    $contestant_table = $wpdb->prefix . 'cvs_contestants';
    $voter_table      = $wpdb->prefix . "cvs_voters";
    $payments_table   = $wpdb->prefix . "cvs_payments";

    function getTotalRow($voter_table, $contestant_table, $contest_id, $wpdb)
    {
        $total_query = "SELECT count(*) AS total FROM $contestant_table INNER JOIN $voter_table ON $contestant_table.id = $voter_table.contestant_id WHERE $contestant_table.contest_id=$contest_id;";
        return $wpdb->get_results($total_query)[0]->total;
    }

    if (isset($_GET["cvs_action"])) {
        $action     = $_GET["cvs_action"];
        $contest_id = $_GET["contest_id"] ?? 0;
        $per_page   = (int) $GLOBALS["cvs_settings"]->voter_pagination;
        $page       = 1;
        $start      = 0;
        $end        = $start + $per_page;

        if (isset($_GET["p"])) {
            $start = ($_GET["p"] - 1) * $per_page;
            $page  = $_GET["p"];
        }

        //    Show voter data
        if ($action === "voters_details") {
            $get_total_vote_query   = "SELECT SUM(number_of_vote) AS total FROM $voters_table WHERE contest_id=$contest_id;";
            $get_total_amount_query = "SELECT SUM(number_of_vote) AS total FROM $voters_table WHERE contest_id=$contest_id && status=TRUE;";
            $get_total_vote         = $wpdb->get_results($get_total_vote_query)[0]->total;
            $get_total_amount       = $wpdb->get_results($get_total_amount_query)[0]->total;
        ?>
<div class="container mt-5">
    <div class="jumbotron">
        <?php
            $contest_data = $wpdb->get_results("SELECT * FROM $contest_table WHERE id = $contest_id")[0];
                    echo "<h1>$contest_data->name</h1>";
                ?>
        <p class="mb-1 mt-4">Total Vote:&nbsp;<b><?php echo $get_total_vote ?? 0; ?></b></p>
        <p class="mb-0">Total Collected Amount:&nbsp;<b><?php echo $get_total_amount ?? 0; ?></b></p>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Contestant</th>
                <th>Voter</th>
                <th>Address</th>
                <th>Vote & Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $voter_query = "SELECT $contestant_table.name AS contestant_name, $contestant_table.image, $voter_table.* FROM $contestant_table INNER JOIN $voter_table ON $contestant_table.id = $voter_table.contestant_id WHERE $contestant_table.contest_id=$contest_id ORDER BY $voter_table.id DESC LIMIT $start, $end;";
                        $voter_data  = $wpdb->get_results($voter_query);
                    foreach ($voter_data as $d) {?>
            <tr>
                <td class="text-center">
                    <img width="100" height="100" class="img-thumbnail" src=<?php echo $d->image ?> />
                    <p class="m-2"><?php echo $d->contestant_name ?></p>
                </td>
                <td>
                    Name: <?php echo $d->name ?><br>
                    Email: <?php echo $d->email ?><br>
                    Phone: <?php echo $d->phone ?><br>
                </td>
                <td style="max-width: 200px">
                    <?php echo "Country: " . $d->country . "<br>Street: " . $d->street . "<br>City: " . $d->city . "<br>State: " . $d->state . "<br>Postal Code: " . $d->zip ?>
                </td>
                <td>
                    <span class="small">Number of Vote</span>: <b><?php echo $d->number_of_vote ?></b> <br>
                    <span class="small">Payment Status</span>:
                    <?php echo $d->status ? '<span class="text-success">Paid</span>' : '<span class="text-danger">Unpaid</span>' ?>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <!--            pagination start-->
    <br>
    <nav>
        <ul class="pagination justify-content-cente">
            <?php
                $rows  = getTotalRow($voter_table, $contestant_table, $contest_id, $wpdb);
                        $pages = ceil($rows / $per_page);
                        $page  = 1;

                        if (isset($_GET["p"])) {
                            $page = $_GET["p"];
                        }

                        if ($page <= 1) {
                            echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="' . $page_url . 'p=' . ($page - 1) . '">Previous</a></li>';
                        }

                        for ($i = 1; $i <= $pages; $i++) {
                            if ($page == $i) {
                                $status = "active";
                            } else {
                                $status = "";
                            }
                            echo '<li class="page-item ' . $status . '"><a class="page-link" href="' . $page_url . 'p=' . $i . '">' . $i . '</a></li>';
                        }

                        if ($page == (int) $pages) {
                            echo '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="' . $page_url . 'p=' . ($page + 1) . '">Next</a></li>';
                        }
                    ?>
        </ul>
    </nav>

</div>

<?php } elseif ('payment_details' === $action) {

            $get_total_paid_query   = "SELECT SUM(amount) FROM $payments_table WHERE contest_id=$contest_id && status = 'paid';";
            $get_total_unpaid_query = "SELECT SUM(amount) FROM $payments_table WHERE contest_id=$contest_id && status != 'paid';";
            $total_payments_query   = "SELECT count(1) FROM $payments_table INNER JOIN $voter_table ON $voter_table.id = $payments_table.voter_id INNER JOIN $contestant_table ON $contestant_table.id = $payments_table.contestant_id WHERE $payments_table.contest_id=$contest_id;";
            $get_total_paid         = $wpdb->get_var($get_total_paid_query);
            $get_total_unpaid       = $wpdb->get_var($get_total_unpaid_query);
            $total_payments_rows    = $wpdb->get_var($total_payments_query);
        ?>

<div class="container mt-5">
    <div class="jumbotron">
        <?php
            $contest_data = $wpdb->get_results("SELECT * FROM $contest_table WHERE id = $contest_id")[0];
                    echo "<h1>$contest_data->name</h1>";
                ?>
        <p class="mb-1 mt-4">Total Payments:&nbsp;<b><?php echo number_format($total_payments_rows ?? 0); ?></b></p>
        <p class="mb-1">Total Paid Amount:&nbsp;<b>$<?php echo number_format($get_total_paid ?? 0); ?></b></p>
        <p class="mb-0">Total Failed Amount:&nbsp;<b>$<?php echo number_format($get_total_unpaid ?? 0); ?></b></p>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Contestant</th>
                <th>Voter</th>
                <th>Payment Details</th>
                <th>Date & Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $payments_query = "SELECT $contestant_table.name AS contestant_name, $contestant_table.image, $voter_table.name,$voter_table.email,$voter_table.phone, $payments_table.* FROM $payments_table INNER JOIN $voter_table ON $voter_table.id = $payments_table.voter_id INNER JOIN $contestant_table ON $contestant_table.id = $payments_table.contestant_id WHERE $payments_table.contest_id=$contest_id ORDER BY $payments_table.id DESC LIMIT $start, $end;";
                        $payments_data  = $wpdb->get_results($payments_query);
                    foreach ($payments_data as $d) {?>
            <tr>
                <td class="text-center">
                    <img width="100" height="100" class="img-thumbnail" src=<?php echo $d->image ?> />
                    <p class="m-2"><?php echo $d->contestant_name ?></p>
                </td>
                <td>
                    Name: <?php echo $d->name ?><br>
                    Email: <?php echo $d->email ?><br>
                    Phone: <?php echo $d->phone ?><br>
                </td>
                <td style="max-width: 300px">
                    Amount: <b>$<?php echo number_format($d->amount ?? 0) ?></b><br>
                    Method: <?php echo ucwords($d->method ?? '') ?><br>
                    <span style="font-size: 14px;">Payment ID: <?php echo $d->payment_id ?? '' ?></span>
                </td>
                <td>
                    Status: <span
                        class="<?php echo esc_attr(['paid' => 'badge-success'][$d->status] ?? 'badge-danger') ?> badge"><?php echo ucwords($d->status ?? '') ?></span>
                    <br>
                    <span style="font-size: 14px;">Date:
                        <?php echo gmdate('d M, Y g:i A', strtotime($d->created_at)) ?></span>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <br>
    <nav>
        <ul class="pagination justify-content-cente">
            <?php
                $pages = ceil($total_payments_rows / $per_page);
                        $page  = 1;

                        if (isset($_GET["p"])) {
                            $page = $_GET["p"];
                        }

                        if ($page <= 1) {
                            echo '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="' . $page_url . 'p=' . ($page - 1) . '">Previous</a></li>';
                        }

                        for ($i = 1; $i <= $pages; $i++) {
                            if ($page == $i) {
                                $status = "active";
                            } else {
                                $status = "";
                            }
                            echo '<li class="page-item ' . $status . '"><a class="page-link" href="' . $page_url . 'p=' . $i . '">' . $i . '</a></li>';
                        }

                        if ($page == (int) $pages) {
                            echo '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="' . $page_url . 'p=' . ($page + 1) . '">Next</a></li>';
                        }
                    ?>
        </ul>
    </nav>
</div>
<?php
    }

    } else {
    ?>
<div class="container mt-5">
    <div class="jumbotron">
        <h1>Votes</h1>
    </div>
    <table class="table table-bordered">
        <thead class="text-center">
            <tr>
                <th>Leaderboard Shortcode</th>
                <th>Contest Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $contest_data = $wpdb->get_results("SELECT * FROM $contest_table");
                foreach ($contest_data as $d) {?>
            <tr>
                <th>[cvs_leaderboard id=<?php echo $d->id ?>/]</th>
                <td><?php echo $d->name ?></td>
                <td class="text-center">
                    <a type="button" class="btn btn-primary"
                        href="<?php echo $page_url . '&cvs_action=voters_details&contest_id=' . $d->id . '&p=1' ?>">See
                        Voters</a>
                </td>
                <td class="text-center">
                    <a type="button" class="btn btn-success"
                        href="<?php echo $page_url . '&cvs_action=payment_details&contest_id=' . $d->id . '&p=1' ?>">See
                        Payments</a>
                </td>
            </tr>
            <?php }?>

            <?php if (empty($contest_data)): ?>
            <tr>
                <td colspan="3" class="text-center p-4">No Contest Found</td>
            </tr>
            <?php endif?>
        </tbody>
    </table>
</div>
<?php }?>