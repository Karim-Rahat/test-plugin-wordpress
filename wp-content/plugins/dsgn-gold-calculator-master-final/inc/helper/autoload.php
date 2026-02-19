<?php
/**
 * Class File Autoloader
 * @package GOLD_CALCULATOR
 * @since 1.0
 */

/**
 * Exit if accessed directly
 */
if(!defined("ABSPATH")) exit;

spl_autoload_register('gold_calculator_autoload');
function gold_calculator_autoload($class) {
	$namespace = 'GOLD_CALCULATOR';
 
	if (strpos($class, $namespace) !== 0) {
		return;
	}
 
	$class = str_replace($namespace, '', $class);
	$class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

	$path = strtolower(GOLD_CALCULATOR_PATH . $class);

 
	if (file_exists($path)) {
		require_once($path);
	}
}