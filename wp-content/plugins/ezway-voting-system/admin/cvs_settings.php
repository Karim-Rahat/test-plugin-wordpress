<?php
    function update_settings($changedData)
    {
        $changedData['paypal_api'] = cvs_encryptData($changedData['paypal_api']);
        $changedData['stripe_sk']  = cvs_encryptData($changedData['stripe_sk']);
        $changedData['stripe_pk']  = cvs_encryptData($changedData['stripe_pk']);

        update_option('cvs_custom_environment', $changedData);

        cvs_init_envireonment_keys(); // reload keys
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $paypal_key       = $_POST["paypal_cid"] ?? '';
        $stripe_sk        = $_POST["stripe_sk"] ?? '';
        $stripe_pk        = $_POST["stripe_pk"] ?? '';
        $paypal_active    = ($_POST["paypal_active"] ?? false) ? "on" : "off";
        $stripe_active    = ($_POST["stripe_active"] ?? false) ? "on" : "off";
        $voter_pagination = $_POST["voter_pagination"] ?? 20;

        $data = [
            "paypal_api"       => $paypal_key,
            "stripe_sk"        => $stripe_sk,
            "stripe_pk"        => $stripe_pk,
            "paypal_active"    => $paypal_active,
            "stripe_active"    => $stripe_active,
            "voter_pagination" => $voter_pagination,
        ];

        update_settings($data);
    }

    if (isset($_GET["reset"]) && $_GET["reset"] === "all") {
        // Create settings file
        $data = [
            "paypal_api"       => "REPLACE_WITH_YOUR_PAYPAL_CLIENT_ID",
            "stripe_sk"        => "REPLACE_WITH_YOUR_STRIPE_SECRETE_KEY",
            "stripe_pk"        => "REPLACE_WITH_YOUR_STRIPE_PUBLISHABLE_KEY",
            "paypal_active"    => "on",
            "stripe_active"    => "on",
            "voter_pagination" => 20,
        ];

        update_settings($data);

        cvs_window_redirect(remove_query_arg('reset'));
    }

    $paypal_disabled = $GLOBALS["cvs_settings"]->paypal_active == "on" ? "checked" : "";
    $stripe_disabled = $GLOBALS["cvs_settings"]->stripe_active == "on" ? "checked" : "";
?>
<div class="container mt-5">
    <div class="jumbotron">
        <h1>Settings</h1>
        <a href="<?php echo home_url() . $_SERVER["REQUEST_URI"] . '&reset=all'; ?>" id="cvs_reset_settings"
            class="btn btn-danger float-right">Reset all</a>
    </div>
    <form method="post">
        <h4>General</h4>
        <hr>
        <div class="form-group row">
            <label for="voter_pagination" class="col-sm-2 col-form-label">Pagination</label>
            <div class="col-sm-10">
                <input name="voter_pagination" type="number" class="form-control" id="voter_pagination"
                    value="<?php echo $GLOBALS["cvs_settings"]->voter_pagination; ?>">
            </div>
        </div>
        <br>
        <h4>API</h4>
        <hr>
        <div class="form-group row">
            <label for="paypal_cid" class="col-sm-2 col-form-label">PayPal client ID</label>
            <div class="col-sm-10">
                <input name="paypal_cid" type="text" class="form-control" id="paypal_cid"
                    value="<?php echo $GLOBALS["cvs_settings"]->paypal_api; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="stripe_pk" class="col-sm-2 col-form-label">Stripe publishable key</label>
            <div class="col-sm-10">
                <input name="stripe_pk" type="text" class="form-control" id="stripe_pk"
                    value="<?php echo $GLOBALS["cvs_settings"]->stripe_pk; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="stripe_sk" class="col-sm-2 col-form-label">Stripe secrete key</label>
            <div class="col-sm-10">
                <input name="stripe_sk" type="text" class="form-control" id="stripe_sk"
                    value="<?php echo $GLOBALS["cvs_settings"]->stripe_sk; ?>">
            </div>
        </div>
        <br>
        <h4>Payment methods</h4>
        <hr>
        <div class="custom-control custom-switch">
            <input name="paypal_active" type="checkbox" class="custom-control-input" id="paypal_active"
                <?php echo $paypal_disabled ?>>
            <label class="custom-control-label" for="paypal_active">PayPal</label>
        </div>
        <div class="custom-control custom-switch">
            <input name="stripe_active" type="checkbox" class="custom-control-input" id="stripe_active"
                <?php echo $stripe_disabled ?>>
            <label class="custom-control-label" for="stripe_active">Stripe</label>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>