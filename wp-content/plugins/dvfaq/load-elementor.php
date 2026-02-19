<?php
namespace Elementordvfaq;

class dvfaqLoadElementor {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function widget_scripts() {
        wp_register_script( 'dvfaq-scripts', plugins_url( '/js/custom.js', __FILE__ ), [ 'jquery' ], false, true );
	}

	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/faqcategory.php' );
        require_once( __DIR__ . '/widgets/faqtopic.php' );
	}

	public function register_widgets() {
		$this->include_widgets_files();

		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\dvfaq_Category() );
        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\dvfaq_Topic() );
	}

	public function __construct() {
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}
}

dvfaqLoadElementor::instance();
?>