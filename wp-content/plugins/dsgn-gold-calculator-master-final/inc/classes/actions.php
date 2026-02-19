<?php

/**
 * All Action Will Be Regestered Here
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

class Actions
{
    use Singleton;

    public function __construct()
    {
        $this->setup_hook();
    }
    public function setup_hook()
    {
        
    }
}
