(function ($) {
    $(document).ready(function () {

        window.cvs_vote = (contest_id, contestent_id) => {
            const contestant = window.cvs_contestents.find((contestant) => contestant.id == contestent_id && contestant.contest_id == contest_id);
            if (!contestant) {
                console.error('Contestant not found');
                return;
            }

            open_voting_modal(contestant);
        };

        window.reload_contestant_list = (contest_id) => {
            $.ajax({
                url: cvs_settings.ajax_url,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'cvs_get_contestant_data',
                    contest_id: contest_id,
                    nonce: cvs_settings.cvs_nonce,
                    sort_by: $('#cvs_contestant_sort_by').val(),
                    filter_by: $('#cvs_contestant_fiilter_by').val(),
                },
                beforeSend: function () {
                    $('#cvs_contestant_list').html('<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4"><div class="cvs_contestent_lazy_loader"></div></div><div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4"><div class="cvs_contestent_lazy_loader"></div></div><div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4"><div class="cvs_contestent_lazy_loader"></div></div><div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4"><div class="cvs_contestent_lazy_loader"></div></div>');
                },
                success: function (contestents) {
                    window.cvs_contestents = contestents;
                    var html = contestents.map((contestant) => {
                        return `<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                <div class="contestent-box"
                                    onclick="cvs_vote(${contestant.contest_id}, ${contestant.id})">
                                    <img src="${contestant.image}" alt="${contestant.name}">
                                    <div class="contestant-info">
                                        <h4 class="contestant-name">${contestant.name}</h4>
                                        <div class="contestant-vote-count d-flex align-items-center mb-2"
                                            style="gap: 12px;">
                                            <span><i class="fa-solid fa-tag"></i> ${contestant.category || ''}</span>
                                            <span class="cvs_no_wrap"><i class="fa-solid fa-heart text-danger"></i> ${contestant.vote || 0}</span>
                                        </div>
                                        <button class="contestant-vote-btn"><i class="fa-solid fa-heart"></i>&nbsp;&nbsp;Vote Now</button>
                                    </div>
                                </div>
                            </div>`;
                    })
                        .join(' ');

                    $('#cvs_contestant_list').html(html);
                },
            });
        };

        reload_contestant_list($('#cvs-wrapper').data('contestid'));

        $('#cvs_contestant_sort_by').on('change', function () {
            reload_contestant_list($('#cvs-wrapper').data('contestid'));
        });

        $('#cvs_contestant_fiilter_by').on('change', function () {
            reload_contestant_list($('#cvs-wrapper').data('contestid'));
        });

        $('#cvs_contestant_reload').on('click', function () {
            reload_contestant_list($('#cvs-wrapper').data('contestid'));
        });

        $('#total_vote').on('change', function () {
            let total_vote = $(this).val();
            if (total_vote < 1) {
                total_vote = 1;
                $(this).val(1);
            }
            $('#cvs_total_amount_to_charge').text(`${total_vote} Vote = $${total_vote} Donation`);
        });

        $('#cvs_payment_proceed_tab_btn').on('click', function () {
            const form = $('#cvs_submit_vote_form');

            form.find('.error-message').fadeOut('fast');

            if (
                !form.find('#voter_fullname').val().trim().length ||
                !form.find('#voter_email').val().trim().length ||
                !form.find('#voter_phone').val().trim().length ||
                !form.find('#total_vote').val().trim().length
            ) {
                form.find('.error-message').text('Please fill all the required fields.');
                form.find('.error-message').fadeIn('fast');
                return;
            }

            $('#cvs_voting_form_user_info_tab').fadeOut('slow', function () {
                $('#cvs_voting_form_payments_tab').show();
            });
        });

        $('#cvs_pay_stripe_Payment').on('click', function () {
            const modal = $('#cvs_voting_modal');
            const form = modal.find('#cvs_submit_vote_form');

            $.ajax({
                url: cvs_settings.ajax_url,
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'cvs_stripe_payment_intend',
                    nonce: cvs_settings.cvs_nonce,
                    amount: form.find('#total_vote').val(),
                },
                beforeSend: function () {
                    form.find('.error-message').fadeOut('fast');
                    modal.find('#cvs_voting_form_loading').show();
                },
                success: function (response) {
                    if (response.success) {
                        cvsStripe.confirmCardPayment(response.body.client_secret, {
                            payment_method: {
                                card: cvsCard
                            }
                        }).then(function (result) {
                            modal.find('#cvs_voting_form_loading').hide();

                            if (result.error) {
                                $('#cvs_stripeErrors').text(result.error.message);
                            } else {
                                if (result.paymentIntent.status === 'succeeded') {
                                    $('#cvs_paymentMethod').val('stripe');
                                    $('#cvs_paymentInfo').val(JSON.stringify(result.paymentIntent));
                                    cvs_submit_voting_form();
                                }
                            }
                        });
                        return;
                    }

                    form.find('.error-message').text(response.message || 'Internal Server Error, Please try again later.');
                    form.find('.error-message').fadeIn('fast');

                    modal.find('#cvs_voting_form_loading').hide();
                },
            });
        });


        $('#cvs_image').on('change', function (event) {
            const preview = $('#cvs_nominee_avatar');
            var reader = new FileReader();
            reader.onload = function () {
                preview.attr('src', reader.result);
                preview.fadeIn('fast');
            };

            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                preview.fadeOut('fast');
            }
        });


        $('#cvs_submit_nomination').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const formData = new FormData(this);

            form.find('.error-message').fadeOut('fast');

            formData.append('action', 'cvs_submit_nomination');
            formData.append('nonce', cvs_settings.cvs_nonce);

            $.ajax({
                url: cvs_settings.ajax_url,
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#cvs_nominee_form_submit_btn span').fadeIn();
                },
                success: function (response) {
                    if (response.success) {
                        $('#cvs_submit_nomination').fadeOut('fast', function () {
                            $('#cvs_nomination_success').fadeIn('fast');
                        });
                        return;
                    }

                    form.find('.error-message').text(response.message || 'Internal Server Error, Please try again later.');
                    form.find('.error-message').fadeIn('fast');
                },
            }).always(function () {
                $('#cvs_nominee_form_submit_btn span').fadeOut();
            });
        });

    });

    function open_voting_modal(contestant) {
        const modal = $('#cvs_voting_modal');

        const form = modal.find('#cvs_submit_vote_form');

        // clear previous opened tab
        modal.find('#cvs_voting_form_payments_tab').hide();
        modal.find('#cvs_voting_success_tab').hide();
        modal.find('#cvs_voting_form_loading').hide();

        $('#cvs_total_amount_to_charge').text(`1 Vote = $1 Donation`);

        modal.find('#cvs_voting_form_user_info_tab').show();

        if (window.cvsCard) {
            window.cvsCard.destroy();
            window.cvsCard = undefined;
        }

        modal.find('#cvs_paypalContainer').html('');
        modal.find('#cvs_stripeCardElement').html('');

        form.find('.error-message').fadeOut('fast');
        form.trigger('reset');

        // update modal content
        modal.find('#cvs_contestant_id').val(contestant.id);
        modal.find('#cvs_contest_id').val(contestant.contest_id);
        modal.find('.contestent-details img').attr('src', contestant.image);
        modal.find('.contestent-details h4.contestant-name').text(contestant.name);
        modal.find('.contestent-details .cvs_vote_count').text(contestant.vote || 0);
        modal.find('.contestent-details .cvs_contestant_category').text(contestant.category || '');
        modal.find('.contestent-details .contestent-description').html(contestant.description || '<i>No description available</i>');

        // prepare payment options
        if (cvs_settings.stripe_active) {
            cvsLoadCdnScript('https://js.stripe.com/v3/', () => {
                window.cvsStripe = Stripe(cvs_settings.stripe_publishable_KEY);
                window.cvsCard = cvsStripe.elements().create('card', {
                    style: {
                        base: {
                            color: '#101828',
                            iconColor: '#1e2939',
                            fontSize: '16px',
                            '::placeholder': {
                                color: '#364153'
                            }
                        },
                        invalid: {
                            color: '#ec003f',
                            iconColor: '#ff2056'
                        }
                    },
                    hidePostalCode: true
                });
                cvsCard.mount('#cvs_stripeCardElement');
                cvsCard.on('change', function (event) {
                    if (event.error) {
                        $('#cvs_stripeErrors').text(event.error.message);
                        $('#cvs_pay_stripe_Payment').prop('disabled', true);
                    } else {
                        $('#cvs_stripeErrors').text('');
                        $('#cvs_pay_stripe_Payment').prop('disabled', false);
                    }
                });
            });
        }

        if (cvs_settings.paypal_active) {
            cvsLoadCdnScript(`https://www.paypal.com/sdk/js?client-id=${cvs_settings.paypal_cliend_ID}&currency=USD&disable-funding=credit,card`, () => {
                paypal.Buttons({
                    locale: "en_US",
                    style: {
                        layout: "horizontal",
                        size: "responsive",
                        color: "gold",
                        shape: "rect",
                        label: "pay",
                        tagline: false
                    },
                    createOrder: function (data, actions) {
                        let number_of_votes = parseInt($('#cvs-wrapper #total_vote').val());
                        if (!number_of_votes) {
                            number_of_votes = 1;
                        }

                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: number_of_votes
                                }
                            }]
                        });
                    },
                    onApprove: function (data, actions) {
                        return actions.order.capture().then(function (orderData) {
                            $('#cvs_paymentMethod').val('paypal');
                            $('#cvs_paymentInfo').val(JSON.stringify(orderData));
                            cvs_submit_voting_form();
                        });
                    }
                }).render('#cvs_paypalContainer');
            });
        }

        modal.modal('show');
    }

    function cvs_submit_voting_form() {
        const modal = $('#cvs_voting_modal');
        const form = modal.find('#cvs_submit_vote_form');

        $.ajax({
            url: cvs_settings.ajax_url,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'cvs_submit_vote',
                nonce: cvs_settings.cvs_nonce,
                contest_id: form.find('#cvs_contest_id').val(),
                contestant_id: form.find('#cvs_contestant_id').val(),
                fullname: form.find('#voter_fullname').val(),
                email: form.find('#voter_email').val(),
                phone: form.find('#voter_phone').val(),
                total_vote: form.find('#total_vote').val(),
                paymewnt_method: form.find('#cvs_paymentMethod').val(),
                payment_result: JSON.parse(form.find('#cvs_paymentInfo').val()),
            },
            beforeSend: function () {
                form.find('.error-message').fadeOut('fast');
                modal.find('#cvs_voting_form_loading').show();
            },
            success: function (response) {

                if (response.success) {
                    modal.find('#cvs_voting_form_payments_tab').fadeOut('fast', function () {
                        modal.find('#cvs_voting_success_tab').fadeIn('slow', 'swing');
                    });
                    reload_contestant_list($('#cvs-wrapper').data('contestid'));
                    return;
                }

                if (response.payment_failed) {
                    modal.find('#cvs_voting_form_payments_tab').fadeOut('fast', function () {
                        modal.find('#cvs_voting_form_user_info_tab').show();
                    });
                }

                form.find('.error-message').text(response.message || 'Internal Server Error, Please try again later.');
                form.find('.error-message').fadeIn('fast');
            },
        })

            .always(function () {
                modal.find('#cvs_voting_form_loading').hide();
            });
    }

    function cvsLoadCdnScript(url, callback = null) {
        // Check if the script is already loaded
        if (document.querySelector(`script[src="${url}"]`)) {
            // If the script is already loaded, call the callback
            if (typeof callback === 'function') callback();
            return;
        }

        // Create a new script element
        const script = document.createElement('script');
        script.src = url;
        script.async = true;
        script.defer = true;

        // Add an event listener to the script to call the callback when it's loaded
        script.onload = () => {
            if (typeof callback === 'function') callback();
        };

        // Add an event listener to the script to log an error if it fails to load
        script.onerror = () => {
            console.error(`Failed to load script: ${url}`);
        };

        // Add the script to the page
        document.head.appendChild(script);
    };
})(jQuery);