<?php
namespace Elementordvfaq\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class dvfaq_Category extends Widget_Base {

	public function get_name() {
		return 'dvfaq-cat';
	}

	public function get_title() {
		return esc_html__( 'FAQ Category', 'dvfaq' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ 'dvfaq-widgets' ];
	}
    
    public function get_script_depends() {
		return [ 'tessera-elementor-dvfaq' ];
	}
    
    public function get_widget_taxonomies() {
        $output_terms = array();
        $args = array (
            'taxonomy' => array('dvfaqcategories'),
            'hide_empty' => 1
        );
        $terms = get_terms($args);
        foreach($terms as $term) {
            $output_terms[$term->term_id] = $term->name;
        }
        return $output_terms;
    } 

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Settings', 'dvfaq' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        
        $this->add_control(
			'categoryid',
			[
				'label' => esc_html__( 'Category', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_widget_taxonomies(),
			]
		);
        
        $this->add_control(
			'topicmenu',
			[
				'label' => esc_html__( 'Topic Menu', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
                    'left'  => esc_html__( 'Left', 'dvfaq' ),
					'right'  => esc_html__( 'Right', 'dvfaq' ),
                    'top'  => esc_html__( 'Top', 'dvfaq' ),
                    'none'  => esc_html__( 'None', 'dvfaq' )
				],
			]
		);
        
        $this->add_control(
			'searchbox',
			[
				'label' => esc_html__( 'Search Box', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
                    'yes'  => esc_html__( 'Enabled', 'dvfaq' ),
					'no'  => esc_html__( 'Disabled', 'dvfaq' )
				],
			]
		);
        
        $this->add_control(
			'skin',
			[
				'label' => esc_html__( 'Skin', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
                    'custom'  => esc_html__( 'Custom', 'dvfaq' ),
					'light'  => esc_html__( 'Light', 'dvfaq' ),
                    'dark'  => esc_html__( 'Dark', 'dvfaq' )
				],
			]
		);
        
        $this->add_control(
			'topictitle', [
				'label' => esc_html__( 'Topic Title', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => ''
			]
		);
        
        $this->add_control(
			'switcher',
			[
				'label' => esc_html__( 'Switcher', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
                    'yes'  => esc_html__( 'Enabled', 'dvfaq' ),
					'no'  => esc_html__( 'Disabled', 'dvfaq' )
				],
			]
		);
        
        $this->end_controls_section();
	}
    
    protected function render() {
		$settings = $this->get_settings_for_display();    
        $categoryid = (int)$settings['categoryid'];
        $topicmenu = $settings['topicmenu'];
        $searchbox = $settings['searchbox'];
        $skin = $settings['skin'];
        $topictitle = $settings['topictitle'];
        $switcher = $settings['switcher'];
        
        if ($categoryid) {
            echo do_shortcode('[dvfaq categoryid="' . $categoryid . '" topicmenu="' . $topicmenu . '" searchbox="' . $searchbox . '" skin="' . $skin . '" topictitle="' . $topictitle . '" switcher="' . $switcher . '"]');
        } else {
            echo '<div class="alert alert-info"><strong>' . esc_html__( 'Please select a category.', 'dvfaq' ) . '</strong></div>';
        }
    }
}
?>