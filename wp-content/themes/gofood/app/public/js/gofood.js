(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    window.openCart = () => {
        const cart = $('#cartDrawer');
        const overlay = $('#cartDrawerOverlay');

        overlay.show();
        cart.addClass('active');
    };

    window.closeCart = () => {
        const cart = $('#cartDrawer');
        const overlay = $('#cartDrawerOverlay');

        overlay.hide();
        cart.removeClass('active');
    };

    /**
     * Re-freshes cart content in the cart drawer.
     *
     * @since 1.0.0
     * @param {Array[]} cart Cart items.
     */
    window.refreshCart = (cart) => {
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        const subtotal = cart.reduce((total, item) => total + ((item.product.discounted_price || item.product.price) * item.quantity), 0);

        $('.gf_cart_count_label').each(function () {
            $(this).text(totalItems);
            if (totalItems) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        const price = (price) => `${GOFOOD_OBJECT.currency_config.symbol}${price.toFixed(GOFOOD_OBJECT.currency_config.number_of_decimals)}`;

        $('#gf_cart_subtotal').text(price(subtotal));
        $('#gf_cart_total').text(price(subtotal));

        let items = cart.reverse().map((item) => {
            const cost = item.product.discounted_price || item.product.price;
            return `<div class="cart-item">
					<button class="btn-reset remove-cart-item" onclick="removeFromCart(${item.product.id})">
						<i class="fas fa-times"></i>
					</button>
					<a href="${item.permalink}"><img src="${item.product.image}"></a>
					<div class="cart-item-description">
						<a href="${item.permalink}"><h5>${item.product.title}</h5></a>
						<div class="d-flex align-items-center justify-content-between">
							<span>${price(cost)}&nbsp;<b>x${item.quantity}</b></span>
							<span class="gofood-Price-amount amount">${price(cost * item.quantity)}</span>
						</div>
					</div>
				</div>`;
        });

        if (!cart.length) {
            items = ['<div class="text-center p-4 text-muted small"><i class="fa-solid fs-4 fa-face-sad-tear mb-2"></i> <p>Your cart is empty.</p></div>'];
            $('#cartDrawer .cart-actions').hide();
        } else {
            $('#cartDrawer .cart-actions').show();
        }

        $('#gf_cart_items_box').html(items.join(''));
    };

    window.addToCart = (product_id, event) => {
        const button = event.target.closest('button');
        const reset_btn_text = button.innerHTML;

        button.style.pointerEvents = 'none';
        button.style.opacity = '0.75';
        button.innerHTML = '<i class="fas fa-spinner fa-spin-pulse fs-5"></i>';

        const resetCartBtn = () => {
            button.style.pointerEvents = 'unset';
            button.style.opacity = 'unset';
            button.innerHTML = reset_btn_text;
        };

        $.ajax({
            type: 'post',
            url: GOFOOD_OBJECT.ajax_url,
            data: {
                action: 'gf_cart',
                type: 'add',
                product_id: product_id,
                nonce: GOFOOD_OBJECT.cart_nonce,
            },
            success: (response) => {
                button.innerHTML = '<i class="fas fa-check fa-beat fs-5"></i>';

                if (response.success) {
                    refreshCart(response.cart || []);
                    openCart();
                }

                setTimeout(() => resetCartBtn(), 2000);
            },
            error: (error) => {
                const message = error.responseJSON.data || 'Something went wrong.';
                alert(message);

                button.innerHTML = '<i class="fas fa-times fa-shake fs-5"></i>';

                setTimeout(() => resetCartBtn(), 2000);
            },
        });
    };

    window.removeFromCart = (product_id) => {
        $('#cartDrawer .loading').show();
        $.ajax({
            type: 'post',
            url: GOFOOD_OBJECT.ajax_url,
            data: {
                action: 'gf_cart',
                type: 'remove',
                product_id: product_id,
                nonce: GOFOOD_OBJECT.cart_nonce,
            },
            success: (response) => {
                refreshCart(response.cart || []);
                $('#cartDrawer .loading').hide();
            },
            error: (error) => {
                const message = error.responseJSON.data || 'Something went wrong.';
                alert(message);
                $('#cartDrawer .loading').hide();
            },
        })
    };

    window.proceedCheckout = (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        formData.append('action', 'gf_checkout');
        formData.append('nonce', GOFOOD_OBJECT.checkout_nonce);

        event.target.style.pointerEvents = 'none';
        event.target.style.opacity = '0.75';
        event.target.style.cursor = 'wait';

        const resetForm = () => {
            event.target.style.pointerEvents = 'unset';
            event.target.style.opacity = 'unset';
            event.target.style.cursor = 'pointer';
        };

        $.ajax({
            type: 'post',
            url: GOFOOD_OBJECT.ajax_url,
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success) {
                    window.location.href = response.url;
                }

                resetForm();
            },
            error: (error) => {
                const message = error.responseJSON.data || 'Something went wrong.';
                alert(message);

                resetForm();
            },
        });
    };

    $(document).ready(() => {
        refreshCart(GOFOOD_OBJECT.cart_snapshot || []);

        $('#user_login_form').submit(function (event) {
            event.preventDefault();
            gf_send_user_account_request(this, 'login');
        });

        $('#user_register_form').submit(function (event) {
            event.preventDefault();
            gf_send_user_account_request(this, 'register');
        });

        $('#update_ma_profile_account').submit(function (event) {
            event.preventDefault();
            gf_send_user_account_request(this, 'update');
        });

    });

    function gf_send_user_account_request(form, type) {

        const formData = new FormData(form);

        if (type === 'update') {
            formData.append('action', 'ma_update_account');
            formData.append('nonce', GOFOOD_OBJECT.ma_profile_nonce);
        } else {
            formData.append('action', 'gf_auth');
            formData.append('type', type);
            formData.append('nonce', GOFOOD_OBJECT.auth_nonce);
        }

        const button = $(form).find('button[type="submit"]');
        const button_text = button.html();

        button.html('<i class="fas fa-spinner fa-spin-pulse fs-5"></i>');
        button.attr('disabled', true);

        const resetForm = () => {
            button.html(button_text);
            button.attr('disabled', false);
        };

        const error_target_element = $(
            {
                'login': '#user_login_error',
                'register': '#user_register_error',
                'update': '#user_ma_profile_update_error',
            }[type]
        );

        $.ajax({
            type: 'post',
            url: GOFOOD_OBJECT.ajax_url,
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success && response.url) {
                    window.location.href = response.url;
                }

                if (response.message) {
                    error_target_element.html(response.message);
                    error_target_element.show();
                } else {
                    error_target_element.hide();
                }

                resetForm();
            },
            error: (error) => {
                const message = error.responseJSON.data || 'Something went wrong.';
                error_target_element.html(message);
                error_target_element.show();

                resetForm();
            },
        });
    }

})(jQuery);
