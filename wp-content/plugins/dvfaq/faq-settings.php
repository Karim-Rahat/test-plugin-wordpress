<?php
add_action( 'cmb2_admin_init', 'dvfaq_register_plugin_options_metabox' );

function dvfaq_register_plugin_options_metabox() {

	$cmb_options = new_cmb2_box( array(
		'id'           => 'dvfaq_option_metabox',
		'title'        => esc_html__( 'Settings', 'dvfaq' ),
		'object_types' => array( 'options-page' ),
		'option_key'      => 'dvfaq_options',
        'parent_slug'     => 'edit.php?post_type=dvfaq',
        'capability'      => 'manage_options',
        'save_button'     => esc_html__( 'Save Settings', 'dvfaq' )
	) );
    
    // Documentation Notice
    
    $cmb_options->add_field( array(
        'name' => '<a href="http://www.wp4life.com/online/dvfaq/index.html" target="_blank">' . esc_attr__( 'Click Here To Read The Documentation', 'dvfaq') . '</a>',
        'desc' => esc_attr__( 'Please take the time to read the documentation. As many support related questions can be answered simply by re-reading the documentation.', 'dvfaq'),
        'type' => 'title',
        'id'   => 'title_doc'
    ));

	// General Settings
    
    $cmb_options->add_field( array(
        'name' => esc_attr__( 'General Settings', 'dvfaq'),
        'type' => 'title',
        'id'   => 'title_general'
    ));
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Heading Level', 'dvfaq'),  
            'id' => 'heading_level',
            'type' => 'select',
            'show_option_none' => false,
            'options' => array(
                'h1' => esc_html__( 'H1', 'dvfaq' ),
                'h2'   => esc_html__( 'H2', 'dvfaq' ),
                'h3'   => esc_html__( 'H3', 'dvfaq' ),
                'h4'   => esc_html__( 'H4', 'dvfaq' ),
                'h5'   => esc_html__( 'H5', 'dvfaq' ),
                'h6'   => esc_html__( 'H6', 'dvfaq' )
            ),
            'default' => 'h4',
        )
    );
        
    $cmb_options->add_field(
        array(
        'name' => esc_attr__( 'Sharing Buttons', 'dvfaq'),
        'desc' => esc_html__( '"Deselect all" to disable social media sharing buttons.', 'dvfaq' ),    
        'id' => 'sharing_btns',
        'type' => 'multicheck_inline',
        'options' => array(
            'email' => esc_html__( 'Email', 'dvfaq' ),
            'twitter'   => esc_html__( 'Twitter', 'dvfaq' ),
            'facebook'   => esc_html__( 'Facebook', 'dvfaq' ),
            'linkedin'   => esc_html__( 'Linkedin', 'dvfaq' ),
            'reddit'   => esc_html__( 'Reddit', 'dvfaq' ),
            'vk'   => esc_html__( 'VK', 'dvfaq' )
        ),
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Comments', 'dvfaq'),  
            'id' => 'comments',
            'type' => 'radio_inline',
            'options' => array(
                'enable' => esc_html__( 'Enable', 'dvfaq' ),
                'disable'   => esc_html__( 'Disable', 'dvfaq' )
            ),
            'default' => 'disable',
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Like Button', 'dvfaq'),  
            'id' => 'like_button',
            'type' => 'radio_inline',
            'options' => array(
                'enable' => esc_html__( 'Enable', 'dvfaq' ),
                'disable'   => esc_html__( 'Disable', 'dvfaq' )
            ),
            'default' => 'disable',
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Dislike Button', 'dvfaq'),  
            'id' => 'dislike_button',
            'type' => 'radio_inline',
            'options' => array(
                'enable' => esc_html__( 'Enable', 'dvfaq' ),
                'disable'   => esc_html__( 'Disable', 'dvfaq' )
            ),
            'default' => 'disable',
        )
    );
        
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Scroll Animation', 'dvfaq'),
            'desc' => esc_attr__('The animation which occurs when a user click a menu item.', 'dvfaq'),    
            'id' => 'scroll_anim',
            'type' => 'radio_inline',
            'options' => array(
                'enable' => esc_html__( 'Enable', 'dvfaq' ),
                'disable'   => esc_html__( 'Disable', 'dvfaq' )
            ),
            'default' => 'enable',
        )
    );
        
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Sticky Menu', 'dvfaq'),  
            'id' => 'sticky_menu',
            'type' => 'radio_inline',
            'options' => array(
                'enable' => esc_html__( 'Enable', 'dvfaq' ),
                'disable'   => esc_html__( 'Disable', 'dvfaq' )
            ),
            'default' => 'disable',
        )
    );
        
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Top Margin', 'dvfaq'), 
            'desc' => esc_attr__( 'The distance between the topic title and the top of the page after the scroll animation (px).', 'dvfaq'), 
            'id' => 'top_spacing',
            'type' => 'text',
            	'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
            'sanitization_cb' => 'absint',
            'default' => 60
        )
    );
    
    $cmb_options->add_field(
        array(
        'name' => esc_attr__( 'Single Q&A Page Skin', 'dvfaq'),   
        'id' => 'single_faq_skin',
        'type' => 'radio_inline',
        'options' => array(
            'custom' => esc_html__( 'Custom', 'dvfaq' ),
            'light'   => esc_html__( 'Light', 'dvfaq' ),
            'dark'   => esc_html__( 'Dark', 'dvfaq' )
        ),
        'default' => 'custom',
        )
    );
    
    // Custom Skin
    
    $cmb_options->add_field( array(
        'name' => esc_attr__( 'Custom Skin', 'dvfaq'),
        'type' => 'title',
        'id'   => 'title_colors'
    ));
    
    $cmb_options->add_field( array(
        'name' => esc_html__( 'Heading Font Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_title_color',
        'type' => 'colorpicker',
        'default' => '#000000',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    $cmb_options->add_field(array(
        'name' => esc_html__( 'Content Font Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_content_color',
        'type' => 'colorpicker',
        'default' => '#444444',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    $cmb_options->add_field( array(
        'name' => esc_html__( 'Link Font Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_link_color',
        'type' => 'colorpicker',
        'default' => '#007acc',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    $cmb_options->add_field(array(
        'name' => esc_html__( 'Background Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_bg_color',
        'type' => 'colorpicker',
        'default' => '#ffffff',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    $cmb_options->add_field(array(
        'name' => esc_html__( 'Border Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_border_color',
        'type' => 'colorpicker',
        'default' => '#dddddd',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    $cmb_options->add_field(array(
        'name' => esc_html__( 'Search Box Font Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_search_color',
        'type' => 'colorpicker',
        'default' => '#444444',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    $cmb_options->add_field(array(
        'name' => esc_html__( 'Search Box Background Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_search_bg_color',
        'type' => 'colorpicker',
        'default' => '#ffffff',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    $cmb_options->add_field(array(
        'name' => esc_html__( 'Search Box Border Color', 'dvfaq'),
        'desc' => '',
        'id' => 'faq_search_border_color',
        'type' => 'colorpicker',
        'default' => '#dddddd',
        'options' => array(
            'alpha' => true, 
        ),
    ));
    
    // Permalinks
    
    $cmb_options->add_field( array(
        'name' => esc_attr__( 'Permalinks Settings', 'dvfaq'),
        'desc' => 'If you like, you may enter custom structures for faq URLs here. You must go to Settings->Permalinks and click the Save Changes button at the bottom of the screen for new settings to take effect.',
        'type' => 'title',
        'id'   => 'title_permalink'
    ));

    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Custom Post Type', 'dvfaq'), 
            'desc' => '', 
            'id' => 'post_type_slug',
            'type' => 'text',
            'default' => 'faq',
            'sanitization_cb' => 'sanitize_title'
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Categories', 'dvfaq'), 
            'desc' => '', 
            'id' => 'category_slug',
            'type' => 'text',
            'default' => 'faq-category',
            'sanitization_cb' => 'sanitize_title'
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Topics', 'dvfaq'), 
            'desc' => '', 
            'id' => 'topic_slug',
            'type' => 'text',
            'default' => 'faq-topic',
            'sanitization_cb' => 'sanitize_title'
        )
    );
    
    // Woocommerce
    
    $cmb_options->add_field( array(
        'name' => esc_attr__( 'Woocommerce Settings', 'dvfaq'),
        'desc' => 'You should upload and set up Woocommerce plugin.',
        'type' => 'title',
        'id'   => 'title_woo'
    ));

    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Product FAQ Tab', 'dvfaq'),  
            'id' => 'product_tab',
            'type' => 'radio_inline',
            'options' => array(
                'enable' => esc_html__( 'Enable', 'dvfaq' ),
                'disable'   => esc_html__( 'Disable', 'dvfaq' )
            ),
            'default' => 'disable',
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Tab Name', 'dvfaq'),
            'id' => 'tab_name',
            'type' => 'text',
            'default' => esc_attr__( 'FAQ', 'dvfaq')
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Tab Priority', 'dvfaq'), 
            'desc' => esc_attr__( 'A number between 1 and 100.', 'dvfaq'), 
            'id' => 'priority',
            'type' => 'text',
            	'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
            'sanitization_cb' => 'absint',
            'default' => 50
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Ask a Question Tab', 'dvfaq'),  
            'id' => 'ask_question_tab',
            'type' => 'radio_inline',
            'options' => array(
                'enable' => esc_html__( 'Enable', 'dvfaq' ),
                'disable'   => esc_html__( 'Disable', 'dvfaq' )
            ),
            'default' => 'disable',
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Tab Name', 'dvfaq'),
            'id' => 'ask_question_tab_name',
            'type' => 'text',
            'default' => esc_attr__( 'Ask a Question', 'dvfaq')
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Tab Priority', 'dvfaq'), 
            'desc' => esc_attr__( 'A number between 1 and 100.', 'dvfaq'), 
            'id' => 'ask_question_priority',
            'type' => 'text',
            	'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
            'sanitization_cb' => 'absint',
            'default' => 51
        )
    );
    
    $cmb_options->add_field(
        array(
            'name' => esc_attr__( 'Contact Form', 'dvfaq'),
            'id' => 'contact_form',
            'type' => 'wysiwyg',
            'options' => array(
                'wpautop' => true, // use wpautop?
                'media_buttons' => false, // show insert/upload button(s)
                'teeny' => true,
                'textarea_rows' => 4,
                'quicktags' => true
            ),
        )
    );

    // Custom CSS
    
    $cmb_options->add_field( array(
        'name' => esc_attr__( 'Custom CSS', 'dvfaq'),
        'desc' => esc_attr__('It works by allowing you to add your own CSS styles, which allows you to override the default styles of the plugin.', 'dvfaq'),
        'type' => 'title',
        'id'   => 'title_custom_css'
    ));
    
    $cmb_options->add_field(
        array(
            'name' => '',  
            'id' => 'custom_css',
            'type' => 'textarea_code',
            	'attributes' => array(
                    'data-codeeditor' => json_encode( array(
                        'codemirror' => array(
				        'mode' => 'css'
                        ),
                    )),
                ),
        )
    );
}

function dvfaq_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		return cmb2_get_option( 'dvfaq_options', $key, $default );
	}

	$opts = get_option( 'dvfaq_options', $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}
?>