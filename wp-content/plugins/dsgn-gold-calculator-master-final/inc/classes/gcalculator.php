<?php

/**
 * Main Class File For The Whole Plugin
 * @package GOLD_CALCULATOR
 * @since 1.0
 */

namespace GOLD_CALCULATOR\Inc\Classes;

/**
 * Exit if accessed directly
 */
if (!defined("ABSPATH")) {
    exit;
}

use GOLD_CALCULATOR\Inc\Traits\Singleton;

class GCALCULATOR
{
    use Singleton;
    public function __construct()
    {
        Assets::get_instance();
        Shortcodes::get_instance();
        Actions::get_instance();
        Form_submission::get_instance();
        Admin_datapage::get_instance();
        Settings_Page::get_instance();
        $this->setup_hooks();
    }

    public function setup_hooks()
    {

    }

}
