<?php
function register_dvfaq_faq_posttype() {
    $dvfaq_post_type_slug = sanitize_title(dvfaq_get_option('post_type_slug', 'faq'));
    $labels = array(
        'name'              => esc_attr__( 'FAQ', 'dvfaq' ),
        'singular_name'     => esc_attr__( 'Question', 'dvfaq' ),
        'add_new'           => esc_attr__( 'Add new question', 'dvfaq' ),
        'add_new_item'      => esc_attr__( 'Add new question', 'dvfaq' ),
        'edit_item'         => esc_attr__( 'Edit question', 'dvfaq' ),
        'new_item'          => esc_attr__( 'New question', 'dvfaq' ),
        'view_item'         => esc_attr__( 'View question', 'dvfaq' ),
        'search_items'      => esc_attr__( 'Search questions', 'dvfaq' ),
        'not_found'         => esc_attr__( 'No question found', 'dvfaq' ),
        'not_found_in_trash'=> esc_attr__( 'No question found in trash', 'dvfaq' ),
        'parent_item_colon' => esc_attr__( 'Parent question:', 'dvfaq' ),
        'menu_name'         => esc_attr__( 'FAQ', 'dvfaq' )
    );

    $taxonomies = array();
    
    $dvfaq_comments = dvfaq_get_option('comments', 'disable');
 
    if ($dvfaq_comments == 'enable') {
        $supports = array('title','editor','thumbnail','excerpt','comments');
    } else {
        $supports = array('title','editor','thumbnail','excerpt');
    }
 
    $post_type_args = array(
        'labels'            => $labels,
        'singular_label'    => esc_attr__('FAQ', 'dvfaq'),
        'public'            => false,
        'exclude_from_search' => false,
        'show_ui'           => true,
        'show_in_nav_menus' => true,
        'publicly_queryable'=> true,
        'query_var'         => true,
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'rewrite'           => array( 'slug' => $dvfaq_post_type_slug, 'with_front' => false ),
        'supports'          => $supports,
        'menu_position'     => 99,
        'menu_icon'         => 'dashicons-sos',
        'taxonomies'        => $taxonomies
    );
    register_post_type('dvfaq',$post_type_args);
}
add_action('init', 'register_dvfaq_faq_posttype');

// Open by default option

function dvfaq_open_accordion_cmb2 ( $meta_boxes ) {
    $prefix = 'dvfaq_cmb2'; // Prefix for all fields
    $meta_boxes['dvfaq_openaccordion'] = array(
        'id' => 'dvfaq_openaccordion',
        'title' => esc_html__( 'Open by default (Accordion)', 'dvfaq'),
        'object_types' => array('dvfaq'), // post type
        'context' => 'side', // normal or side
        'priority' => 'high', // default or high
        'show_names' => false, // Show field names on the left
        'fields' => array(
            array(
                'name'    => esc_html__( 'Open by default (Accordion)', 'dvfaq'),
                'desc'    => '',
                'id'      => $prefix . '_openaccordion',
                'type'    => 'radio_inline',
                'options' => array(
                    'yes' => esc_html__( 'Yes', 'dvfaq' ),
                    'no'   => esc_html__( 'No', 'dvfaq' ),
                ),
                'default' => 'no'
            )
        ),
    );

    return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', 'dvfaq_open_accordion_cmb2' );

// Likes - Dislikes

function dvfaq_likes_dislikes_cmb2 ( $meta_boxes ) {
    $prefix = '_dvfaq'; // Prefix for all fields
    $meta_boxes['dvfaq_likesdislikes'] = array(
        'id' => 'dvfaq_likesdislikes',
        'title' => esc_html__( 'Likes & Dislikes', 'dvfaq'),
        'object_types' => array('dvfaq'), // post type
        'context' => 'side', // normal or side
        'priority' => 'default', // default or high
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name'    => esc_html__( 'Likes', 'dvfaq'),
                'desc'    => '',
                'id'      => $prefix . '_likes',
                'type' => 'text',
            	'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
            'sanitization_cb' => 'absint',
            ),
            array(
                'name'    => esc_html__( 'Dislikes', 'dvfaq'),
                'desc'    => '',
                'id'      => $prefix . '_dislikes',
                'type' => 'text',
            	'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
            'sanitization_cb' => 'absint',
            )
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb2_meta_boxes', 'dvfaq_likes_dislikes_cmb2' );


// Register categories

function dvfaq_faq_taxonomy() {
    $dvfaq_category_slug = sanitize_title(dvfaq_get_option('category_slug', 'faq-category'));
    register_taxonomy(
        'dvfaqcategories',
        'dvfaq',
        array(
            'labels' => array(
                'name' => esc_attr__( 'FAQ Categories', 'dvfaq' ),
                'add_new_item' => esc_attr__( 'Add new category', 'dvfaq' ),
                'new_item_name' => esc_attr__( 'New category', 'dvfaq' )
            ),
            'show_ui' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'hierarchical' => true,
            'query_var' => true,
            'show_in_quick_edit' => true,
            'rewrite' => array( 'slug' => $dvfaq_category_slug )
        )
    );
}
add_action( 'init', 'dvfaq_faq_taxonomy', 0 );

// Register topics

function dvfaq_faq_topic() {
    $dvfaq_topic_slug = sanitize_title(dvfaq_get_option('topic_slug', 'faq-category'));
    register_taxonomy(
        'dvfaqtopics',
        'dvfaq',
        array(
            'labels' => array(
                'name' => esc_attr__( 'FAQ Topics', 'dvfaq' ),
                'add_new_item' => esc_attr__( 'Add new topic', 'dvfaq' ),
                'new_item_name' => esc_attr__( 'New topic', 'dvfaq' )
            ),
            'show_ui' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'hierarchical' => true,
            'query_var' => true,
            'show_in_quick_edit' => true,
            'rewrite' => array( 'slug' => $dvfaq_topic_slug )
        )
    );
}
add_action( 'init', 'dvfaq_faq_topic', 0 );

// Remove post type support

remove_post_type_support( 'dvfaq', 'post-formats' );
remove_post_type_support( 'dvfaq', 'trackbacks' );
?>