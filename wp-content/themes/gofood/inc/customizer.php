<?php
/**
 * GoFood Theme Customizer
 *
 * @package goFood
 */

/**
 * Registers the GoFood theme customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager object.
 */
function gofood_customize_register( $wp_customize ) {

	// Header Settings.
	$wp_customize->add_section(
		'gofood_header_settings',
		array(
			'title'    => __( 'Header Settings', 'gofood' ),
			'priority' => 20,
		)
	);

	// Top Bar - Delivery Text.
	$wp_customize->add_setting(
		'gofood_delivery_text',
		array(
			'default'           => 'Free delivery on orders over $20',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_delivery_text',
		array(
			'label'   => __( 'Delivery Notice Text', 'gofood' ),
			'section' => 'gofood_header_settings',
			'type'    => 'text',
		)
	);

	// Top Bar - Phone Number.
	$wp_customize->add_setting(
		'gofood_phone_number',
		array(
			'default'           => '+1 234-567-8900',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_phone_number',
		array(
			'label'   => __( 'Phone Number', 'gofood' ),
			'section' => 'gofood_header_settings',
			'type'    => 'text',
		)
	);

	// Top Bar - Email Address.
	$wp_customize->add_setting(
		'gofood_email_address',
		array(
			'default'           => 'help@gofood.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_email_address',
		array(
			'label'   => __( 'Email Address', 'gofood' ),
			'section' => 'gofood_header_settings',
			'type'    => 'email',
		)
	);

	// Hero Banner Settings.
	$wp_customize->add_section(
		'gofood_hero_settings',
		array(
			'title'    => __( 'Hero Banner', 'gofood' ),
			'priority' => 30,
		)
	);

	// Hero Title.
	$wp_customize->add_setting(
		'gofood_hero_title',
		array(
			'default'           => 'Delicious Food Delivered To Your Doorstep',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_hero_title',
		array(
			'label'   => __( 'Hero Title', 'gofood' ),
			'section' => 'gofood_hero_settings',
			'type'    => 'text',
		)
	);

	// Hero Description.
	$wp_customize->add_setting(
		'gofood_hero_description',
		array(
			'default'           => 'Order your favorite meals from local restaurants and get it delivered fast.',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_hero_description',
		array(
			'label'   => __( 'Hero Description', 'gofood' ),
			'section' => 'gofood_hero_settings',
			'type'    => 'textarea',
		)
	);

	// Hero Button Text.
	$wp_customize->add_setting(
		'gofood_hero_button_text',
		array(
			'default'           => 'Order Now',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_hero_button_text',
		array(
			'label'   => __( 'Button Text', 'gofood' ),
			'section' => 'gofood_hero_settings',
			'type'    => 'text',
		)
	);

	// Hero Button URL.
	$wp_customize->add_setting(
		'gofood_hero_button_url',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_hero_button_url',
		array(
			'label'   => __( 'Button URL', 'gofood' ),
			'section' => 'gofood_hero_settings',
			'type'    => 'url',
		)
	);

	// Hero Image.
	$wp_customize->add_setting(
		'gofood_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'gofood_hero_image',
			array(
				'label'     => __( 'Hero Image', 'gofood' ),
				'section'   => 'gofood_hero_settings',
				'mime_type' => 'image',
			)
		)
	);

	// Footer Settings.
	$wp_customize->add_section(
		'gofood_footer_settings',
		array(
			'title'    => __( 'Footer Settings', 'gofood' ),
			'priority' => 40,
		)
	);

	// Copyright Text.
	$wp_customize->add_setting(
		'gofood_copyright_text',
		array(
			'default'           => '© ' . gmdate( 'Y' ) . ' GoFood LTD.',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_copyright_text',
		array(
			'label'   => __( 'Copyright Text', 'gofood' ),
			'section' => 'gofood_footer_settings',
			'type'    => 'textarea',
		)
	);

	// Facebook URL.
	$wp_customize->add_setting(
		'gofood_facebook_url',
		array(
			'default'           => 'https://facebook.com',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_facebook_url',
		array(
			'label'   => __( 'Facebook URL', 'gofood' ),
			'section' => 'gofood_footer_settings',
			'type'    => 'url',
		)
	);

	// Instagram URL.
	$wp_customize->add_setting(
		'gofood_instagram_url',
		array(
			'default'           => 'https://instagram.com',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'gofood_instagram_url',
		array(
			'label'   => __( 'Instagram URL', 'gofood' ),
			'section' => 'gofood_footer_settings',
			'type'    => 'url',
		)
	);
}

add_action( 'customize_register', 'gofood_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function gofood_customize_preview_js() {
	wp_enqueue_script( 'gofood-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), GOFOOD__VERSION, true );
}
add_action( 'customize_preview_init', 'gofood_customize_preview_js' );
