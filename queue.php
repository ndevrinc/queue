<?php
/**
 * Plugin Name: Queue
 * Version: 1.0
 * Description: This plugin will serve as a base for a solid queue system on a WordPress installation
 * Author: Andrea Fuggetta <afuggetta@ndevr.io>
 * Author URI: https://ndevr.io
 * Plugin URI: https://ndevr.io
 * Text Domain: queue
 * Domain Path: /languages
 * @package Queue
 */


/**
 * Registering PSR-4 compliant namespaces
 * TODO: Move this into a class?
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {

	// project-specific namespace prefix
	$prefix = 'Queue\\Lib\\';

	// base directory for the namespace prefix
	$base_dir = __DIR__ . '/lib/';

	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$relative_class = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	// if the file exists, require it
	if (file_exists($file)) {
		require $file;
	}
});