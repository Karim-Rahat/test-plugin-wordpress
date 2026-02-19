<?php
namespace Elementordvfaq\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class dvfaq_Topic extends Widget_Base {

	public function get_name() {
		return 'dvfaq-topic';
	}

	public function get_title() {
		return esc_html__( 'FAQ Topic', 'dvfaq' );
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
            'taxonomy' => array('dvfaqtopics'),
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
			'title', [
				'label' => esc_html__( 'Title', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => ''
			]
		);
        
        $this->add_control(
			'topicid',
			[
				'label' => esc_html__( 'Topic', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_widget_taxonomies(),
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
        
        $this->add_control(
			'paginate',
			[
				'label' => esc_html__( 'Maximum number of articles', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 99,
				'step' => 1,
				'default' => '',
			]
		);
        
        $this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => [
                    'ASC'  => esc_html__( 'Ascending', 'dvfaq' ),
                    'DESC'  => esc_html__( 'Descending', 'dvfaq' )
				],
			]
		);
        
        $this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Order By', 'dvfaq' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
                    'post_date'  => esc_html__( 'Date', 'dvfaq' ),
					'title'  => esc_html__( 'Title', 'dvfaq' ),
                    'menu_order'  => esc_html__( 'Menu Order', 'dvfaq' ),
					'rand'  => esc_html__( 'Random', 'dvfaq' ),
                    'comment_count'  => esc_html__( 'Comment Count', 'dvfaq' )
				],
			]
		);
        
        $this->end_controls_section();
	}
    
    protected function render() {
		$settings = $this->get_settings_for_display();
        $title = $settings['title'];
        $topicid = (int)$settings['topicid'];
        $searchbox = $settings['searchbox'];
        $skin = $settings['skin'];
        $switcher = $settings['switcher'];
        $paginate = (int)$settings['paginate'];
        $order = $settings['order'];
        $orderby = $settings['orderby'];
        
        if ($topicid) {
            echo do_shortcode('[dvfaqtopic title="' . $title . '" topicid="' . $topicid . '" skin="' . $skin . '" searchbox="' . $searchbox . '" switcher="' . $switcher . '" paginate="' . $paginate . '" order="' . $order . '" orderby="' . $orderby . '"]');
        } else {
            echo '<div class="alert alert-info"><strong>' . esc_html__( 'Please select a topic.', 'dvfaq' ) . '</strong></div>';
        }
    }
}
?>