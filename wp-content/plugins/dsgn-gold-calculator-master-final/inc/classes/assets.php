<?php
/**
 * Assets Loader Class
 * @package GOLD_CALCULATOR
 * @since 1.0
 */
namespace GOLD_CALCULATOR\Inc\Classes;

/**
 * Exit if accessed directly
 */
if (!defined("ABSPATH"))
	exit;

use GOLD_CALCULATOR\Inc\Traits\Singleton;
class Assets
{
	use Singleton;
	public function __construct()
	{
		$this->setup_hooks();
	}
	public function setup_hooks()
	{
		add_action("admin_enqueue_scripts", [$this, 'admin_style']);
		add_action("wp_enqueue_scripts", [$this, 'public_styles']);
	}
	public function public_styles()
	{

		$contact_form_url=get_page_with_shortcode();
		wp_register_style(
			"dsgn-gold-calculator-style",
			GOLD_CALCULATOR_URL . "/assets/css/style.css",
			[],
			filemtime(GOLD_CALCULATOR_PATH . "/assets/css/style.css")
		);
		wp_register_style(
			"dsgn-gold-form-style",
			GOLD_CALCULATOR_URL . "/assets/css/form.css",
			[],
			filemtime(GOLD_CALCULATOR_PATH . "/assets/css/form.css")
		);



		wp_register_script(
			"dsgn-gold-calc-localdb",
			GOLD_CALCULATOR_URL . "/assets/js/localdb.js",
			["jquery"],
			filemtime(GOLD_CALCULATOR_PATH . "/assets/js/localdb.js"),
			true
		);
		

	wp_register_script(
  'dsgn-gold-calc-form',
  GOLD_CALCULATOR_URL . '/assets/js/form.js',
  ['jquery'],
  filemtime(GOLD_CALCULATOR_PATH . '/assets/js/form.js'),
  true
);

wp_localize_script('dsgn-gold-calc-form', 'goldSell', array(
  'ajax_url' => admin_url('admin-ajax.php'),
  'nonce'    => wp_create_nonce('gold_sell_action'),
));

wp_enqueue_script('dsgn-gold-calc-form');

		wp_register_script(
			"dsgn-gold-mobile-tab-script", // Handle: a unique name for the script
			GOLD_CALCULATOR_URL . "/assets/js/mble-tab.js", // URL to the JS file
			["jquery"], // Dependencies: this script requires jQuery to load first
			filemtime(GOLD_CALCULATOR_PATH . "/assets/js/mble-tab.js"), // Version: uses file modification time for cache-busting
			true // Load in footer: true loads the script just before </body> for better performance
		);


		wp_register_script(
			"dsgn-gold-price-calculation-script",
			GOLD_CALCULATOR_URL . "/assets/js/price-calculation.js",
			["jquery"],
			filemtime(GOLD_CALCULATOR_PATH . "/assets/js/price-calculation.js"),
			true
		);

		wp_register_script(
			"dsgn-gold-calculator-scrolling-script",
			GOLD_CALCULATOR_URL . "/assets/js/scrolling.js",
			["jquery"],
			filemtime(GOLD_CALCULATOR_PATH . "/assets/js/scrolling.js"),
			true
		);

		wp_register_script(
			"dsgn-gold-calculator-tab-original-script",
			GOLD_CALCULATOR_URL . "/assets/js/tab-original.js",
			["jquery"],
			filemtime(GOLD_CALCULATOR_PATH . "/assets/js/tab-original.js"),
			true
		);

		$calculator_data = [
			"ajax_url" => admin_url("admin-ajax.php"),
			"nonce" => wp_create_nonce("gold-calculator-nonce"),
		];
		wp_localize_script(
			"dsgn-gold-calculator-tab-original-script",
			"calculator_data",
			$calculator_data
		);

		

		wp_localize_script(
			"dsgn-gold-calc-localdb",
			"contact_form_data",
			["contact_form_url" => $contact_form_url]
		);


		// if (is_singular() && has_shortcode(get_post()->post_content, 'dsgn_gold_calculator')) {
		wp_enqueue_style("dsgn-gold-calculator-style");
		wp_enqueue_style("dsgn-gold-form-style");
		wp_enqueue_script("dsgn-gold-calc-localdb");
		wp_enqueue_script("dsgn-gold-mobile-tab-script");
		wp_enqueue_script("dsgn-gold-price-calculation-script");
		wp_enqueue_script("dsgn-gold-calculator-scrolling-script");
		wp_enqueue_script("dsgn-gold-calculator-tab-original-script");
		wp_enqueue_script("dsgn-gold-calc-form");
		// }
	}
	public function admin_style()
	{
	}



}