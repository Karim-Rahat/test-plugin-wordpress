<?php

/**
 * All Shortcodes Used Here
 * @package GOLD_CALCULATOR
 * @version 1.00
 */

namespace GOLD_CALCULATOR\Inc\Classes;

/**
 * Exit if accessed directly
 */
if (!defined("ABSPATH")) {
    exit;
}

use GOLD_CALCULATOR\Inc\Traits\Singleton;

class Shortcodes
{
    use Singleton;

    public function __construct()
    {
        $this->register_shortcodes();
    }
    public function register_shortcodes()
    {
        add_shortcode("dsgn_gold_calculator", [$this, "dsgn_gold_calculator_shortcode"]);
        add_shortcode("dsgn_gold_calculator_banner", [$this, "dsgn_gold_calculator_shortcode_banner"]);
        add_shortcode("dsgn_gold_gold_form", [$this, "dsgn_gold_gold_form"]);
    }

        // Helper to get settings
    private function get_setting($key, $default = '')
    {
        $options = get_option('gold_calc_settings', []);
        return isset($options[$key]) ? $options[$key] : $default;
    }
    
    public function dsgn_gold_calculator_shortcode()
    {

        $title = $this->get_setting('form_header_title', 'Default Title');
        $subtitle = $this->get_setting('form_header_subtitle', '');
        ob_start();
        
        include GOLD_CALCULATOR_PATH . "/templates/calculator-html.php";
        return ob_get_clean();
    }

    public function dsgn_gold_calculator_shortcode_banner()
    {
        $html = '';
        $html .= "
           
<section class='dsgn-banner-marquee'>
  <p class='we-pay dsgn-m-para'>We Pay :</p>
  <div class='marquee-wrapper'>
    <div class='marquee-content' id='goldMarqueeText'>
  
    </div>
  </div>
</section>

        ";
        return $html;
    }

    public function dsgn_gold_gold_form()
    {
        
        $title = $this->get_setting('form_header_title', 'Default Title');
        $subtitle = $this->get_setting('form_header_subtitle', '');
        $instructions = $this->get_setting('form_instructions', '');
        ob_start();
        include GOLD_CALCULATOR_PATH . "/templates/gold_form-html.php";
        return ob_get_clean();


    }
}
