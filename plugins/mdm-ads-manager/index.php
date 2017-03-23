<?php
/**
 * The plugin bootstrap file
 * This file is read by WordPress to generate the plugin information in the plugin admin area.
 * This file also defines plugin parameters, registers the activation and deactivation functions, and defines a function that starts the plugin.
 * @link    http://midwestfamilymarketing.com
 * @since   1.0.0
 * @package mdm_ads_manager
 *
 * @wordpress-plugin
 * Plugin Name: MDM Ads Manager
 * Plugin URI:  http://midwestfamilymarketing.com
 * Description: Tools to aid development, including git deployment, style guides, and server information
 * Version:     1.0.0
 * Author:      Mid-West Family Marketing
 * Author URI:  http://midwestfamilymarketing.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: mdm_ads_manager
 * Domain Path: /i18n
 */

// If this file is called directly, abort
if ( !defined( 'WPINC' ) ) {
    die( 'Bugger Off Script Kiddies!' );
}

function mdm_ads_manager_autoload_register( $className ) {
	// Check and make damned sure we're only loading things from this namespace
	if( strpos( $className, 'Mdm\AdsManager' ) === false ) {
		return false;
	}
	// replace backslashes
	$className = strtolower( str_replace( '\\', '/', $className ) );
	// Ensure there is no slash at the beginning of the classname
	$className = ltrim( $className, '/' );
	// Append full path to class
	$path  = plugin_dir_path( __FILE__ ) . 'includes/' . "{$className}.php";
	// Replace some known constants
	$path = str_ireplace( 'Mdm/', '', $path );
	$path = str_ireplace( 'AdsManager/', '', $path );
	// include the class...
	include_once( $path );
}

function mdm_ads_manager_php_version_error() {
	$message = __( 'Irks! MDM Ads Manager requires minimum PHP v5.3.0 to run. Please update your version of PHP or disable the MDM Ads Manager plugin.', 'mdm_ads_manager' );
	printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
}

function mdm_ads_manager_activate() {
	\Mdm\AdsManager\Activator::activate();
}

function mdm_ads_manager_run() {
	// If version is less than minimum, register notice
	if( version_compare( '5.3.0', phpversion(), '>=' ) ) {
		add_action( 'admin_notices', 'mdm_ads_manager_php_version_error' );
		return;
	}
	// Register Autoloader
	spl_autoload_register( 'mdm_ads_manager_autoload_register' );
	// Register activation hook
	register_activation_hook( __FILE__, 'mdm_ads_manager_activate' );
	// Instantiate our plugin
	$plugin = call_user_func( array( '\\Mdm\\AdsManager', 'get_instance' ) );
	// And finally, run it...
	$plugin->burn_baby_burn();
}
mdm_ads_manager_run();
