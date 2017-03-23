<?php
/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin admin area.
 * This file also defines plugin parameters, registers the activation and deactivation functions, and defines a function that starts the plugin.
 * @link    http://www.midwestfamilymarketing.com
 * @since   1.0.0
 * @package mdm_recent_posts
 *
 * @wordpress-plugin
 * Plugin Name: Better Recent Posts by Mid-West
 * Plugin URI:  https://github.com/MDMDevOps/mdm-recent-posts/
 * Description: A better recent posts plugin, which provides several options not included in other widgets
 * Version:     1.0.0
 * Author:      Mid-West Digital Marketing
 * Author URI:  http://www.midwestfamilymarketing.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: mdm_recent_posts
 */

// If this file is called directly, abort
if ( !defined( 'WPINC' ) ) {
    die( 'Bugger Off Script Kiddies!' );
}

function mdm_recent_posts_autoload_register( $className ) {
	// Check and make damned sure we're only loading things from this namespace
	if( strpos( $className, 'Mdm\Recent_Posts' ) === false ) {
		return false;
	}
	// replace backslashes
	$className = strtolower( str_replace( '\\', '/', $className ) );
	// Ensure there is no slash at the beginning of the classname
	$className = ltrim( $className, '/' );
	// Append full path to class
	$path  = plugin_dir_path( __FILE__ ) . 'includes/' . "{$className}.php";
	// Replace some known constants
	$path = str_replace( 'mdm/', '', $path );
	$path = str_replace( 'recent_posts/', '', $path );
	// include the class...
	include_once( $path );
}

function mdm_recent_posts_php_version_error() {
	$message = __( 'Irks! Better Recent Posts by Mid-West requires minimum PHP v5.3.0 to run. Please update your version of PHP or disable the Better Recent Posts by Mid-West plugin.', 'mdm_recent_posts' );
	printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
}

function activate_mdm_recent_posts() {

}


function run_mdm_recent_posts() {
	// If version is less than minimum, register notice
	if( version_compare( '5.3.0', phpversion(), '>=' ) ) {
		add_action( 'admin_notices', 'mdm_recent_posts_php_version_error' );
		return;
	}
	// Register Autoloader
	spl_autoload_register( 'mdm_recent_posts_autoload_register' );
	// Register activation hook
	register_activation_hook( __FILE__, 'activate_mdm_recent_posts' );
	// Instantiate our plugin
	$plugin = call_user_func( array( '\\Mdm\\Recent_Posts', 'get_instance' ) );
	// And finally, run it...
	$plugin->burn_baby_burn();
}
run_mdm_recent_posts();