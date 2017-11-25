<?php
/*
Plugin Name: ChronosWP
Plugin URI: http://zaroutski.com
Description: WordPress theme powerup
Version: 0.1.1
Author: Anton Zaroutski
Author URI: http://zaroutski.com
Plugin Type: Piklist
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// CHRONOS
// Set up constants
define( 'CHRONOSWP_VER', '0.1' );
define( 'CHRONOSWP_PATH', plugin_dir_path( __FILE__ ) );
define( 'CHRONOSWP_SRC_PATH', CHRONOSWP_PATH . 'src' );
define( 'CHRONOSWP_THEME_SRC_PATH', get_stylesheet_directory() . '/chronoswp/Addon/' );
define( 'CHRONOSWP_ASSETS_PATH', CHRONOSWP_PATH . 'assets' );
define( 'CHRONOSWP_CSS_PATH', CHRONOSWP_ASSETS_PATH . '/css' );
// define( 'CHRONOSWP_VENDOR_PATH', CHRONOSWP_PATH . 'vendor' );
define( 'CHRONOSWP_URL', plugin_dir_url( __FILE__ ) );
define( 'CHRONOSWP_SRC_URL', CHRONOSWP_URL . 'src' );
define( 'CHRONOSWP_ADDONS_URL', CHRONOSWP_SRC_URL . '/Addon' );
define( 'CHRONOSWP_ELEMENT_CONTROLLER_CLASS_NAME_SUFFIX', 'Controller' );
define( 'CHRONOSWP_BASENAME', plugin_basename( __FILE__ ) );

// "src" directories
define( 'CHRONOSWP_ADDONS_PATH', CHRONOSWP_SRC_PATH . '/Addon' );
define( 'CHRONOSWP_TEMPLATE_PATH_SUFFIX', '/templates' );
define( 'CHRONOSWP_ELEMENTS_PATH', CHRONOSWP_SRC_PATH . '/Element' );
define( 'CHRONOSWP_DEPENDENCY_PATH', CHRONOSWP_SRC_PATH . '/Dependency' );

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once CHRONOSWP_PATH . '/chronoswp-scripts.php';
require_once CHRONOSWP_PATH . '/chronoswp-functions.php';
require_once CHRONOSWP_PATH . '/chronoswp-hooks.php';

// Register an autoloader
spl_autoload_register( 'chwp_autoload' );

/**
 * ChronosWP autoloader implementation
 *
 * @param $class
 */
function chwp_autoload( $class )
{
	$baseDirectories = [
		get_stylesheet_directory() . '/chronoswp/',
		__DIR__ . '/src/'
	];

	foreach ($baseDirectories as $baseDirectory) {

		$prefix = 'ChronosWP\\';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$file = $baseDirectory . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
			break;
		}

	}

}

/**
 * Hack to get the code going until the concrete way of launching has been developed
 *
 * @todo fix this up
 */
function chwp_run() {
    $chwp = new \ChronosWP\Core();
}

add_action('init', 'chwp_run', 1);

// @todo write functionality for global settings
$admin = new ChronosWP\Admin();

// @todo Introduce the dedicated directories for addons

function myplugin_activate() {
	$chronoswp = new \ChronosWP\Core();
}
register_activation_hook( __FILE__, 'myplugin_activate' );

/**
 * Make sure that piklist plugin is installed and active
 */
add_action( 'init', function() {
	if( is_admin() ) {
		// include_once( 'includes/class-piklist-checker.php' );
		if(!\ChronosWP\PiklistChecker::check(__FILE__ ) ) {
			return;
		}
	}
});
