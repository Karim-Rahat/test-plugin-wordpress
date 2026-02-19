<div id="cvs-wrapper" class="contestant-holder" data-contestid="<?php echo esc_attr($contest_id); ?>">
    <div id="cvs_wrapper_actions">
        <div class="d-flex align-items-center" style="gap: 10px;">
            <select class="form-select" id="cvs_contestant_fiilter_by">
                <option disabled>Filter By —</option>
                <option value="all" selected>All</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo esc_attr($category) ?>"><?php echo $category ?></option>
                <?php endforeach?>
            </select>
            <select class="form-select" id="cvs_contestant_sort_by">
                <option disabled>Sort By —</option>
                <option value="top" selected>Top Votes</option>
                <option value="az_asc">Name ASC</option>
                <option value="az_desc">Name DESC</option>
            </select>
        </div>
        <button id="cvs_contestant_reload"><i class="fa-solid fa-arrows-rotate"></i> Refresh</button>
    </div>
    <div id="cvs_contestant_list" class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="cvs_contestent_lazy_loader"></div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="cvs_contestent_lazy_loader"></div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="cvs_contestent_lazy_loader"></div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="cvs_contestent_lazy_loader"></div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="cvs_voting_modal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body position-relative">
                    <div class="mb-1 d-flex align-items-center justify-content-between">
                        <button class="btn btn-sm cvs_back_to_contest" data-bs-dismiss="modal"><i
                                class="fa-solid fa-chevron-left"></i> Back to Contest</button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="cvs_vote_modal_content" class="p-2">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Contestant</h3>
                                <div class="contestent-details">
                                    <img src="" class="rounded shadow">
                                    <div class="mt-3">
                                        <h4 class="contestant-name cvs_text_primary"></h4>
                                        <div class="contestant-vote-count d-flex justify-content-center align-items-center"
                                            style="gap: 12px;">
                                            <span class="d-block"><i class="fa-solid fa-tag"></i> <span
                                                    class="cvs_contestant_category"></span></span>
                                            <span class="d-block cvs_no_wrap"><i
                                                    class="fa-solid fa-heart text-danger"></i> Vote:
                                                <span class="cvs_vote_count"></span></span>
                                        </div>
                                        <div class="contestent-description"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4 mt-md-0">
                                <div class="border p-3 rounded">
                                    <h3>Vote</h3>
                                    <form method="post" id="cvs_submit_vote_form">
                                        <input type="hidden" name="contest_id" id="cvs_contest_id">
                                        <input type="hidden" name="contestant_id" id="cvs_contestant_id">
                                        <p class="alert alert-danger px-3 py-2 error-message" style="display:none;"></p>
                                        <div id="cvs_voting_form_user_info_tab">
                                            <div class="mb-3">
                                                <label clalss="form-label" for="voter_fullname">Full Name <span
                                                        style="color:red">*</span></label>
                                                <input class="form-control" type="text" name="voter_fullname"
                                                    id="voter_fullname" required="required" aria-required="true">
                                            </div>
                                            <div class="mb-3">
                                                <label clalss="form-label" for="voter_email">Email Address <span
                                                        style="color:red">*</span></label>
                                                <input class="form-control" type="email" name="voter_email"
                                                    id="voter_email" required="required" aria-required="true">
                                            </div>
                                            <div class="mb-3">
                                                <label clalss="form-label" for="voter_phone">Phone <span
                                                        style="color:red">*</span></label>
                                                <input class="form-control" type="tel" name="voter_phone"
                                                    id="voter_phone" required="required" aria-required="true">
                                            </div>
                                            <div class="mb-4">
                                                <label clalss="form-label" for="total_vote"
                                                    class="formbuilder-select-label">Number of votes <span
                                                        style="color:red">*</span></label>
                                                <input class="form-control" min="1" value="1" type="number"
                                                    name="total_vote" id="total_vote" required="required"
                                                    aria-required="true">
                                                <small class="cvs_text_primary fw-semibold"
                                                    id="cvs_total_amount_to_charge">1 Vote = $1 Donation</small>
                                            </div>
                                            <div class="text-center">
                                                <button type="button" id="cvs_payment_proceed_tab_btn"><i
                                                        class="fa-solid fa-credit-card"></i>&nbsp;&nbsp;Proceed to
                                                    Payment</button>
                                            </div>
                                        </div>
                                        <div id="cvs_voting_form_payments_tab" style="display: none;">
                                            <input type="hidden" name="paymewnt_method" id="cvs_paymentMethod"
                                                value="paypal">
                                            <input type="hidden" name="payment_result" id="cvs_paymentInfo">
                                            <div>
                                                <?php
                                                    $stripe_active = ($GLOBALS["cvs_settings"]->stripe_active ?? 'off') === 'on';
                                                    $paypal_active = ($GLOBALS["cvs_settings"]->paypal_active ?? 'off') === 'on';
                                                if ($stripe_active): ?>
                                                <div>
                                                    <div id="cvs_stripeCardElement" class="p-3 rounded bg-light">
                                                    </div>
                                                    <div id="cvs_stripeErrors"
                                                        class="text-danger mt-2 font-medium text-sm">
                                                    </div>
                                                    <div class="text-center mt-3">
                                                        <button id="cvs_pay_stripe_Payment" disabled type="button"
                                                            class="cvs_primary_btn">Pay With Card</button>
                                                    </div>
                                                </div>
                                                <?php endif?>
                                                <?php if ($stripe_active && $paypal_active): ?>
                                                <div class="text-muted text-center px-4 py-3">Or</div>
                                                <?php endif?>
                                                <?php if ($paypal_active): ?>
                                                <div id="cvs_paypalContainer"></div>
                                                <?php endif?>
                                            </div>
                                        </div>
                                        <div id="cvs_voting_success_tab" style="display: none;">
                                            <div class="text-center p-4 pb-2 cvs_alert">
                                                <i class="fa-solid fa-circle-check fs-2 fa-bounce cvs_text_primary"></i>
                                                <h5 class="modal-title mb-2" id="successModalLabel">Thank You</h5>
                                                <p class="mb-0">Your Vote has been successfully submitted.</p>
                                            </div>
                                            <div class="modal-footer justify-content-center border-0">
                                                <button type="button" class="cvs_primary_btn" data-bs-dismiss="modal"
                                                    aria-label="Close"><i
                                                        class="fa-solid fa-thumbs-up"></i>&nbsp;Done</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cvs_voting_form_loading" style="display:none;">
                        <i class="fa-solid fa-spinner fs-4 fa-spin-pulse"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>