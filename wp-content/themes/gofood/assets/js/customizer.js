/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {
	// Header text
	wp.customize('gofood_delivery_text', function (value) {
		value.bind(function (newval) {
			$('.top-bar-delivery-text').text(newval);
		});
	});

	wp.customize('gofood_phone_number', function (value) {
		value.bind(function (newval) {
			$('.top-bar-phone').text(newval);
		});
	});

	wp.customize('gofood_email_address', function (value) {
		value.bind(function (newval) {
			$('.top-bar-email').text(newval);
		});
	});

	// Hero section
	wp.customize('gofood_hero_title', function (value) {
		value.bind(function (newval) {
			$('.hero-title').text(newval);
		});
	});

	wp.customize('gofood_hero_description', function (value) {
		value.bind(function (newval) {
			$('.hero-description').html(newval);
		});
	});

	wp.customize('gofood_hero_button_text', function (value) {
		value.bind(function (newval) {
			$('.hero-button').text(newval);
		});
	});

	wp.customize('gofood_hero_button_url', function (value) {
		value.bind(function (newval) {
			$('.hero-button').attr('href', newval);
		});
	});

	wp.customize('gofood_hero_image', function (value) {
		value.bind(function (newval) {
			if (newval) {
				var imageUrl = wp.customize.attachment(newval).get().url;
				$('.hero-image').attr('src', imageUrl);
			} else {
				$('.hero-image').attr('src', '/wp-content/themes/gofood/assets/images/hero-banner.webp');
			}
		});
	});

	// Footer
	wp.customize('gofood_copyright_text', function (value) {
		value.bind(function (newval) {
			$('.copyright-text').html(newval);
		});
	});

	wp.customize('gofood_facebook_url', function (value) {
		value.bind(function (newval) {
			$('.social-facebook').attr('href', newval);
		});
	});

	wp.customize('gofood_instagram_url', function (value) {
		value.bind(function (newval) {
			$('.social-instagram').attr('href', newval);
		});
	});
}(jQuery));
