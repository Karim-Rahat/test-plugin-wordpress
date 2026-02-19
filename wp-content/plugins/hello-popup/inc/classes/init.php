<?php

/**
 * Hello Popup Plugin Main File
 * @package Hello Popup
 * @since 1.0.0
 */
namespace HELLOPOPUP\Inc\Classes;

if (! defined("ABSPATH")) {
    exit;
}

use HELLOPOPUP\Inc\Traits\Singleton;

class Init
{
    use Singleton;

    public function __construct()
    {
        Assets::get_instance();
        Setup::get_instance();
        $this->setup_hooks();
    }

    public function setup_hooks()
    {

    }
}