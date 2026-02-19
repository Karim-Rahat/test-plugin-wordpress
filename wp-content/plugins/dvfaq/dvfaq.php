<?php
/**
 * Plugin Name: DVFAQ
 * Plugin URI: https://codecanyon.net/user/egemenerd/portfolio
 * Description: WordPress FAQ Manager
 * Version: 1.3.2
 * Author: Egemenerd
 * Author URI: http://codecanyon.net/user/egemenerd
 * License: http://codecanyon.net/licenses
 * Text Domain: dvfaq
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* Language File */

add_action( 'init', 'dvfaqdomain' );

function dvfaqdomain() {
	load_plugin_textdomain( 'dvfaq', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/* ---------------------------------------------------------
Custom Metaboxes - https://github.com/WebDevStudios/CMB2
----------------------------------------------------------- */

// Check for  PHP version
$dvfaqdir = ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) ? __DIR__ : dirname( __FILE__ );

if ( file_exists(  $dvfaqdir . '/cmb2/init.php' ) ) {
    require_once  $dvfaqdir . '/cmb2/init.php';
} elseif ( file_exists(  $dvfaqdir . '/CMB2/init.php' ) ) {
    require_once  $dvfaqdir . '/CMB2/init.php';
}
    
/* Custom post type */
include_once('faq-posttype.php');
    
/* Shortcodes */
include_once('faq-shortcodes.php');
    
/* Plugin settings */
include_once('faq-settings.php');

/* Likes - Dislikes */
include_once('faq-likes.php');

/* Widgets */
include_once('faq-widgets.php');


function dvfaq_loaded_function(){
    /* Woocommerce */
    if ( class_exists( 'woocommerce' ) ) {
        include_once('faq-woo.php');
    }
}

add_action('plugins_loaded','dvfaq_loaded_function');

/* Add Body Class to the Single Question Page */

add_filter( 'body_class', 'dvfaq_body_class' );
function dvfaq_body_class( $classes ) {
    $dvfaq_single_faq_skin = dvfaq_get_option('single_faq_skin', 'custom');
    if ( is_singular( 'dvfaq' ) ) {
        $classes[] = 'dvfaq-' . $dvfaq_single_faq_skin;
    }
    return $classes;
}

/* Admin Styles */

function dvfaq_admin_scripts(){
    wp_enqueue_style('dvfaq-admin', plugin_dir_url( __FILE__ ) . 'css/admin.css', false, '1.0');
}
add_action( 'admin_enqueue_scripts', 'dvfaq_admin_scripts' );

/* Register Front-End Scripts */

function dvfaq_scripts() {
    wp_enqueue_script('jquery');
    $dvfaq_enable_sticky = dvfaq_get_option('sticky_menu', 'disable');
    $dvfaq_enable_like = dvfaq_get_option('like_button', 'disable');
    $dvfaq_enable_dislike = dvfaq_get_option('dislike_button', 'disable');
    
    if ($dvfaq_enable_sticky == 'enable') {
        wp_register_script('theia-sticky-sidebar', plugin_dir_url( __FILE__ ).'js/theia-sticky-sidebar.min.js', array( 'jquery' ), '1.7', true);
        $dvfaq_scrolltop = dvfaq_get_option('top_spacing', 60);
        if (empty($dvfaq_scrolltop)) {
            $dvfaq_scrolltop = 60;
        }
        $dvfaq_script_param = array(
            "dvfaq_scrolltop" => $dvfaq_scrolltop
        );
        wp_localize_script('theia-sticky-sidebar', 'dvfaq_script_vars', $dvfaq_script_param);
    }
    if (($dvfaq_enable_like == 'enable') || ($dvfaq_enable_dislike == 'enable')) {
        wp_enqueue_script( 'dvfaq-like-it', trailingslashit( plugin_dir_url( __FILE__ ) ).'js/like-it.js', array('jquery'), '1.0', true );
 
        wp_localize_script( 'dvfaq-like-it', 'dvfaqlikeit', array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ));
    }

    wp_register_script('dvfaq-scripts', plugin_dir_url( __FILE__ ).'js/custom.js', array( 'jquery' ), '1.0', true);
}
add_action('wp_enqueue_scripts','dvfaq_scripts');

/* Register Front-End Styles */

function dvfaq_styles() {  
    wp_enqueue_style('dvfaq-styles', plugin_dir_url( __FILE__ ) . 'css/style.css', true, '1.0');
    if ( is_rtl() ) {
        wp_enqueue_style('dvfaq-rtl', plugin_dir_url( __FILE__ ) . 'css/rtl.css', true, '1.0'); 
    }
    wp_enqueue_style('dvfaq-skins', plugin_dir_url( __FILE__ ) . 'css/skins.css', true, '1.0');
    
    $dvfaq_faq_title_color = dvfaq_get_option('faq_title_color', '#000000');
    $dvfaq_faq_content_color = dvfaq_get_option('faq_content_color', '#444444');
    $dvfaq_faq_link_color = dvfaq_get_option('faq_link_color', '#007acc');
    $dvfaq_faq_bg_color = dvfaq_get_option('faq_bg_color', '#ffffff');
    $dvfaq_faq_border_color = dvfaq_get_option('faq_border_color', '#dddddd');
    $dvfaq_faq_search_color = dvfaq_get_option('faq_search_color', '#444444');
    $dvfaq_faq_search_bg_color = dvfaq_get_option('faq_search_bg_color', '#ffffff');
    $dvfaq_faq_search_border_color = dvfaq_get_option('faq_search_border_color', '#dddddd');
    $dvfaq_custom_css = dvfaq_get_option('custom_css');

    $dvfaq_inline_style = '';
    
    if ($dvfaq_faq_title_color != '#000000') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-accordion-header.dvfaq-active-header,.dvfaq-custom h1.dvfaq-cat-title,.dvfaq-custom h2.dvfaq-cat-title,.dvfaq-custom h3.dvfaq-cat-title,.dvfaq-custom h4.dvfaq-cat-title,.dvfaq-custom h5.dvfaq-cat-title,.dvfaq-custom h6.dvfaq-cat-title,.dvfaq-custom .dvfaq-accordion-header,.dvfaq-custom h1.dvfaq-menu-title,.dvfaq-custom h2.dvfaq-menu-title,.dvfaq-custom h3.dvfaq-menu-title,.dvfaq-custom h4.dvfaq-menu-title,.dvfaq-custom h5.dvfaq-menu-title,.dvfaq-custom h6.dvfaq-menu-title,.dvfaq-custom .dvfaq-faq-menu li a,.dvfaq-custom .dvfaq-faq-menu li a,.dvfaq-custom .dvfaq-readmore a,.dvfaq-custom .dvfaq-switcher li,.dvfaq-custom .dvfaq-pagination-button {color:' . $dvfaq_faq_title_color . ';}';
    }
    
    if ($dvfaq_faq_content_color != '#444444') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-accordion-container,.dvfaq-custom .dvfaq-accordion-container p,.dvfaq-custom .dvfaq-social-share-btns li,.dvfaq-custom .dvfaq-social-share-btns a,.dvfaq-custom .dvfaq-like-title,.dvfaq-custom .dvfaq-accordion-header,.dvfaq-custom .dvfaq-live-search-container .dvfaq-live-search-icon:before {color: ' . $dvfaq_faq_content_color . ';}';
    }
    
    if ($dvfaq_faq_bg_color != '#ffffff') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-accordion-container,.dvfaq-custom .dvfaq-faq-menu,.dvfaq-custom h1.dvfaq-cat-title,.dvfaq-custom h2.dvfaq-cat-title,.dvfaq-custom h3.dvfaq-cat-title,.dvfaq-custom h4.dvfaq-cat-title,.dvfaq-custom h5.dvfaq-cat-title,.dvfaq-custom h6.dvfaq-cat-title,.dvfaq-custom .dvfaq-switcher-open,.dvfaq-custom .dvfaq-switcher-close {background:' . $dvfaq_faq_bg_color . ';}';
    }
    
    if ($dvfaq_faq_border_color != '#dddddd') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-custom h1.dvfaq-cat-title,.dvfaq-custom h2.dvfaq-cat-title,.dvfaq-custom h3.dvfaq-cat-title,.dvfaq-custom h4.dvfaq-cat-title,.dvfaq-custom h5.dvfaq-cat-title,.dvfaq-custom h6.dvfaq-cat-title,.dvfaq-custom .dvfaq-accordion-container,.dvfaq-custom .dvfaq-accordion-header,.dvfaq-custom .dvfaq-social-share-btns li,.dvfaq-custom .dvfaq-like-it,.dvfaq-custom .dvfaq-dislike-it,.dvfaq-custom h1.dvfaq-menu-title,.dvfaq-custom h2.dvfaq-menu-title,.dvfaq-custom h3.dvfaq-menu-title,.dvfaq-custom h4.dvfaq-menu-title,.dvfaq-custom h5.dvfaq-menu-title,.dvfaq-custom h6.dvfaq-menu-title,.dvfaq-custom .dvfaq-faq-menu,.dvfaq-custom .dvfaq-faq-menu li a,.dvfaq-custom .dvfaq-switcher li,.dvfaq-custom .dvfaq-pagination-button {border-color:' . $dvfaq_faq_border_color . ';}';
    }
    
    if ($dvfaq_faq_search_color != '#444444') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-live-search-container input[type="text"],.dvfaq-custom .dvfaq-live-search-container input[type="text"]:focus {color:' . $dvfaq_faq_search_color . ' !important;}';
    }
    
    if ($dvfaq_faq_search_bg_color != '#ffffff') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-live-search-container input[type="text"]{background:' . $dvfaq_faq_search_bg_color . ' !important;}';
    }
    
    if ($dvfaq_faq_search_border_color != '#dddddd') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-live-search-container input[type="text"]{border-color:' . $dvfaq_faq_search_border_color . ' !important;}';
    }
    
    if ($dvfaq_faq_link_color != '#007acc') {
        $dvfaq_inline_style .= '.dvfaq-custom .dvfaq-accordion-header:hover,.dvfaq-custom .dvfaq-social-share-btns a:hover,.dvfaq-custom .dvfaq-faq-menu li a:hover,.dvfaq-custom .dvfaq-switcher li:hover,.dvfaq-custom .dvfaq-readmore a:hover,.dvfaq-custom .dvfaq-pagination-button:hover,.dvfaq-pagination-button.active {color:' . $dvfaq_faq_link_color . ';}';
    }
    
    if (!empty($dvfaq_custom_css)) {
        $dvfaq_inline_style .= str_replace(array("\r", "\n"), '', $dvfaq_custom_css);
    }
    
    wp_add_inline_style( 'dvfaq-skins', $dvfaq_inline_style );
}
add_action('wp_enqueue_scripts','dvfaq_styles');

/*---------------------------------------------------
FAQ Categories Columns
----------------------------------------------------*/

add_action( "manage_edit-dvfaqcategories_columns", 'dvfaqcategories_add_col' );
add_filter( "manage_dvfaqcategories_custom_column", 'dvfaqcategories_show_id', 10, 3 );

function dvfaqcategories_add_col( $columns )
{
    unset($columns['slug']);
    unset($columns['description']);
    return $columns + array ( 'dvfaqcategoriesid' => esc_attr__('Shortcode', 'dvfaq') );
}

function dvfaqcategories_show_id( $ver, $name, $id )
{
    $term = get_term( $id, 'dvfaqcategories' );
    $term_id = $term->term_id;
    if ($name === 'dvfaqcategoriesid') { ?>
        <input class='dvfaq-shortcode' value='[dvfaq categoryid="<?php echo esc_attr($term_id); ?>" topicmenu="left" searchbox="yes" skin="custom" topictitle="Topics" switcher="yes"]' readonly='readonly' onfocus="this.select();" />
    <?php }
}

/*---------------------------------------------------
FAQ Topics Columns
----------------------------------------------------*/

add_action( "manage_edit-dvfaqtopics_columns", 'dvfaqtopics_add_col' );
add_filter( "manage_dvfaqtopics_custom_column", 'dvfaqtopics_show_id', 10, 3 );

function dvfaqtopics_add_col( $columns )
{
    unset($columns['slug']);
    unset($columns['description']);
    return $columns + array ( 'dvfaqtopicsid' => esc_attr__('Shortcode', 'dvfaq') );
}

function dvfaqtopics_show_id( $ver, $name, $id )
{
    $term = get_term( $id, 'dvfaqtopics' );
    $term_name = $term->name;
    $term_id = $term->term_id;
    if ($name === 'dvfaqtopicsid') { ?>
        <input class='dvfaq-shortcode' value='[dvfaqtopic title="<?php echo esc_attr($term_name); ?>" topicid="<?php echo esc_attr($term_id); ?>" skin="custom" searchbox="yes" switcher="yes" paginate="" order="ASC" orderby="date"]' readonly='readonly' onfocus="this.select();" />
    <?php }
}

/*---------------------------------------------------
Add ID colum to faq
----------------------------------------------------*/
add_action( "manage_dvfaq_posts_columns", 'dvfaq_add_col' );
add_filter( "manage_dvfaq_posts_custom_column", 'dvfaq_show_id', 10, 3 );

function dvfaq_add_col( $columns )
{
    return $columns + array ( 
        'dvfaqid' => esc_attr__('Shortcode', 'dvfaq')
    );
}

function dvfaq_show_id( $column, $post_id )
{
    if ($column === 'dvfaqid') {
    ?>
    <input class='dvfaq-shortcode' value='[dvfaqsingle headinglevel="h3" postid="<?php echo esc_attr($post_id); ?>" skin="custom"]' readonly='readonly' onfocus="this.select();" />
    <?php
    }
}

/*---------------------------------------------------
Custom Tinymce button
----------------------------------------------------*/
 

if ( is_admin() ) {
add_action('init', 'dvfaq_shortcodes_add_button');  
function dvfaq_shortcodes_add_button() {  
   if ( current_user_can('edit_posts') && current_user_can('edit_pages') )  
   {  
     add_filter('mce_external_plugins', 'dvfaq_add_plugin', 10);  
     add_filter('mce_buttons', 'dvfaq_register_button', 10);  
   }  
} 

function dvfaq_register_button($buttons) {
    array_push($buttons, "dvfaq_mce_button");
    return $buttons;  
}  

function dvfaq_add_plugin($plugin_array) {
    $plugin_array['dvfaq_mce_button'] = plugin_dir_url( __FILE__ ) . 'js/shortcodes.js';
    return $plugin_array;  
}
}

/*---------------------------------------------------
Sections
----------------------------------------------------*/

function dvfaq_faq_content($categoryid, $random_number) {
    include('faq-content.php');
}

function dvfaq_faq_menu($categoryid, $random_number, $topictitle) {
    include('faq-menu.php');
}

function dvfaq_faq_search() {
    include('faq-search.php');
}

/*---------------------------------------------------
Sharing Buttons
----------------------------------------------------*/

function dvfaq_faq_sharing() {
    include('faq-sharing.php');
}

function dvfaq_sharing_to_content( $content ) {  
    $postid = get_the_ID();
    if( is_singular('dvfaq') ) {
        $content .= '[dvsharing postid="' . $postid . '"]';
    }
    return $content;
}


$dvfaq_sharing_btns = dvfaq_get_option('sharing_btns');

if (!empty($dvfaq_sharing_btns)) {
    add_action('dvfaq_after_content', 'dvfaq_faq_sharing', 9);
    add_filter( 'the_content', 'dvfaq_sharing_to_content' );
}

/* ---------------------------------------------------------
ELEMENTOR
----------------------------------------------------------- */

include_once('elementor.php');

/* Create a new category */

function dvfaq_add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'dvfaq-widgets',
		[
			'title' => esc_html__( 'DVFAQ', 'dvfaq' ),
			'icon' => 'fa fa-plug',
		]
	);

}
add_action( 'elementor/elements/categories_registered', 'dvfaq_add_elementor_widget_categories' );

/* ---------------------------------------------------------
CMB2 WP 5.4.2+ compatibility
----------------------------------------------------------- */

function dvfaq_cmb2_admin_scripts( $hook ) {
	global $wp_version;
	if( version_compare( $wp_version, '5.4.2' , '>=' ) ) {
		wp_localize_script(
		  'wp-color-picker',
		  'wpColorPickerL10n',
		  array(
			'clear'            => esc_html__( 'Clear', 'dvfaq' ),
			'clearAriaLabel'   => esc_html__( 'Clear color', 'dvfaq' ),
			'defaultString'    => esc_html__( 'Default', 'dvfaq' ),
			'defaultAriaLabel' => esc_html__( 'Select default color', 'dvfaq' ),
			'pick'             => esc_html__( 'Select Color', 'dvfaq' ),
			'defaultLabel'     => esc_html__( 'Color value', 'dvfaq' )
		  )
		);
	}
}
add_action( 'admin_enqueue_scripts', 'dvfaq_cmb2_admin_scripts', 99 );

?>