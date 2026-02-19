<?php
function dvfaq_get_topics_cmb2 () {
    $dvfaq_topics_args = array(
        'taxonomy' => 'dvfaqtopics',
        'orderby' => 'title',
        'order' =>  'ASC',
        'fields' => 'id=>name'
    );
    $dvfaq_topics = get_terms($dvfaq_topics_args);
    return $dvfaq_topics;
}

/* META BOX */
function dvfaq_woo_meta_cmb2 ( $meta_boxes ) {
    $prefix = 'dvfaq_cmb2'; // Prefix for all fields
    $meta_boxes['dvfaq_woometa'] = array(
        'id' => 'dvfaq_woometa',
        'title' => esc_html__( 'Product FAQ', 'dvfaq'),
        'object_types' => array('product'), // post type
        'context' => 'side', // normal or side
        'priority' => 'default', // default or high
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'id' => $prefix . '_woo_tab',
                'type' => 'radio_inline',
                'options' => array(
                    'enable' => esc_html__( 'Enable', 'dvfaq' ),
                    'disable' => esc_html__( 'Disable', 'dvfaq' ),
                ),
                'default' => 'disable'
            ),
            array(
                'id' => $prefix . '_woo_title',
                'name' => esc_attr__( 'Title', 'dvfaq'),
                'type' => 'text',
                'default' => ''
            ),
            array(
                'id' => $prefix . '_woo_topic',
                'name' => esc_attr__( 'Topic', 'dvfaq'),
                'show_option_none' => true,
                'type'    => 'select',
                'options' => dvfaq_get_topics_cmb2()
            ),
            
            array(
                'id' => $prefix . '_woo_skin',
                'name' => esc_attr__( 'Skin', 'dvfaq'),
                'type' => 'select',
                'show_option_none' => false,
                'options' => array(
                    'custom' => esc_html__( 'custom', 'dvfaq' ),
                    'light'   => esc_html__( 'light', 'dvfaq' ),
                    'dark'   => esc_html__( 'dark', 'dvfaq' )
                )
            ),
            array(
                'id' => $prefix . '_woo_searchbox',
                'type' => 'radio_inline',
                'name' => esc_attr__( 'Search Box', 'dvfaq'),
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'dvfaq' ),
                    'no' => esc_html__( 'No', 'dvfaq' ),
                ),
                'default' => 'yes'
            ),
            array(
                'id' => $prefix . '_woo_switcher',
                'type' => 'radio_inline',
                'name' => esc_attr__( 'Switcher', 'dvfaq'),
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'dvfaq' ),
                    'no' => esc_html__( 'No', 'dvfaq' ),
                ),
                'default' => 'no'
            ),
            array(
                'id' => $prefix . '_woo_paginate',
                'name' => esc_attr__( 'Paginate', 'dvfaq'),
                'type' => 'text',
            	'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
                'sanitization_cb' => 'absint',
                'default' => 0
            ),
            array(
                'id' => $prefix . '_woo_order',
                'type' => 'radio_inline',
                'name' => esc_attr__( 'Order', 'dvfaq'),
                'options' => array(
                    'ASC' => esc_html__( 'ASC', 'dvfaq' ),
                    'DESC' => esc_html__( 'DESC', 'dvfaq' ),
                ),
                'default' => 'ASC'
            ),
            array(
                'id' => $prefix . '_woo_orderby',
                'name' => esc_attr__( 'Order By', 'dvfaq'),
                'type' => 'select',
                'show_option_none' => false,
                'options' => array(
                    'date' => esc_html__( 'Date', 'dvfaq' ),
                    'ID'   => esc_html__( 'ID', 'dvfaq' ),
                    'title'   => esc_html__( 'Title', 'dvfaq' ),
                    'rand'   => esc_html__( 'Random', 'dvfaq' ),
                    'comment_count'   => esc_html__( 'Comment Count', 'dvfaq' )
                )
            )
        ),
    );

    return $meta_boxes;
}

/* PRODUCT TAB */

function dvfaq_new_product_tab( $tabs ) {
    $woo_tab = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_tab', true );
    if ($woo_tab == 'enable') {
    $dvfaq_tab_name = dvfaq_get_option('tab_name', 'FAQ');
    $dvfaq_priority = dvfaq_get_option('priority', 50);
	$tabs['dvfaq_tab'] = array(
		'title' 	=> $dvfaq_tab_name,
		'priority' 	=> $dvfaq_priority,
		'callback' 	=> 'dvfaq_new_product_tab_content'
	);
	return $tabs;
    } else {
        return $tabs;
    }
    
}
function dvfaq_new_product_tab_content() {
    $woo_title = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_title', true );
    $woo_topic = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_topic', true );
    $woo_skin = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_skin', true );
    $woo_searchbox = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_searchbox', true );
    $woo_switcher = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_switcher', true );
    $woo_paginate = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_paginate', true );
    $woo_order = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_order', true );
    $woo_orderby = get_post_meta( get_the_ID(), 'dvfaq_cmb2_woo_orderby', true );
    
    echo do_shortcode('[dvfaqtopic title="' . esc_attr($woo_title) . '" topicid="' . esc_attr($woo_topic) . '" skin="' . esc_attr($woo_skin) . '" searchbox="' . esc_attr($woo_searchbox) . '" switcher="' . esc_attr($woo_switcher) . '" paginate="' . esc_attr($woo_paginate) . '" order="' . esc_attr($woo_order) . '" orderby="' . esc_attr($woo_orderby) . '"]');
}

$dvfaq_product_tab = dvfaq_get_option('product_tab', 'disable');
if ($dvfaq_product_tab == 'enable') {
    add_filter( 'cmb2_meta_boxes', 'dvfaq_woo_meta_cmb2' );
    add_filter( 'woocommerce_product_tabs', 'dvfaq_new_product_tab' );
}

/* ASK A QUESTION TAB */

function dvfaq_contact_product_tab( $tabs ) {
    $dvfaq_tab_name = dvfaq_get_option('ask_question_tab_name', 'Ask A Question');
    $dvfaq_priority = dvfaq_get_option('ask_question_priority', 51);
	$tabs['dvfaq_tab_2'] = array(
		'title' 	=> $dvfaq_tab_name,
		'priority' 	=> $dvfaq_priority,
		'callback' 	=> 'dvfaq_contact_product_tab_content'
	);
	return $tabs;
    
}
function dvfaq_contact_product_tab_content() {
    $content = dvfaq_get_option('contact_form');
    echo do_shortcode(wp_kses_post($content));
}

$dvfaq_ask_question_tab = dvfaq_get_option('ask_question_tab', 'disable');
if ($dvfaq_ask_question_tab == 'enable') {
    add_filter( 'woocommerce_product_tabs', 'dvfaq_contact_product_tab' );
}
?>