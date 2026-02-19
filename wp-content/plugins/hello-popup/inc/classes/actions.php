<?php
namespace HELLOPOPUP\Inc\Classes;

if (! defined('ABSPATH')) {
    exit;
}
use HELLOPOPUP\Inc\Traits\Singleton;

class Actions
{
    use Singleton;

    public function __construct()
    {
        $this->setup_hooks();
    }

    public function setup_hooks()
    {
    }
}