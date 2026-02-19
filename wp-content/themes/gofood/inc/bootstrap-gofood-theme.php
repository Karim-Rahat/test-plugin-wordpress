<?php
/**
 * Bootstrap the GoFood Theme.
 *
 * This file is responsible for loading the theme's functionality.
 *
 * @package goFood
 */

/**
 * The code that runs during tehem activation.
 * This action is documented in /app/bootstrap/class-gofood-activator.php
 */
function activate_gofood() {
	include_once GOFOOD__THEME_DIR . '/app/bootstrap/class-gofood-activator.php';
	GoFood_Activator::activate();
}

/**
 * The code that runs during tehem deactivation.
 * This action is documented in /app/bootstrap/class-gofood-deactivator.php
 */
function deactivate_gofood() {
	include_once GOFOOD__THEME_DIR . '/app/bootstrap/class-gofood-deactivator.php';
	GoFood_Deactivator::deactivate();
}

add_action( 'after_switch_theme', 'activate_gofood' );
add_action( 'switch_theme', 'deactivate_gofood' );

/**
 * Includes the GoFood class.
 */
require_once GOFOOD__THEME_DIR . '/app/bootstrap/class-gofood.php';

/**
 * Initializes and runs the GoFood theme.
 *
 * This function creates an instance of the GoFood class
 * and invokes its run method to execute the theme's functionality.
 *
 * @since 1.0.0
 */
function run_gofood() {
	$gofood = new GoFood();
	$gofood->run();
}

run_gofood();
