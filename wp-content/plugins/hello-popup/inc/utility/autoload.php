<?php

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Autoloader for Hello Popup classes
 *
 * This function will automatically include class files based on the class name.
 * It assumes that classes are namespaced under 'HELLOPOPUP' and that the class files are located in the plugin's directory.
 *
 * @param string $class The fully qualified class name to load.
 * @return void
 */

spl_autoload_register('hello_popup_autoloader');

function hello_popup_autoloader($class)
{
    $namespace = 'HELLOPOPUP';

    if (strpos($class, $namespace) !== 0) {
        return;
    }

    $class = str_replace($namespace, '', $class);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    $path = strtolower(HELLO_POPUP_DIR . $class);

    if (file_exists($path)) {

        require_once $path;
    } else {
        // Optionally log an error or handle the case where the file does not exist
       // error_log("Autoload failed for class: $class. File not found: $path");
    }
}
