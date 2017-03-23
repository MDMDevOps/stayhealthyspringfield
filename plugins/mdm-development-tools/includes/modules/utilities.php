<?php

namespace Mdm\DevTools\Modules;

class Utilities extends \Mdm\DevTools {

	/**
	 * Helper function to use relative URLs
	 * @since 1.0.0
	 */
	public static function url( $url = '' ) {
		return parent::$plugin_url . ltrim( $url, '/' );
	}

	/**
	 * Helper function to use relative paths
	 * @since 1.0.0
	 */
	public static function path( $path = '' ) {
		return parent::$plugin_path . ltrim( $path, '/' );
	}

	/**
	 * Helper function to print debugging statements to the window
	 * @since 1.0.0
	 */
	public static function expose( $expression ) {
		echo '<pre class="expose">';
		print_r( $expression );
		echo '</pre>';
	}

	public static function get_log_path() {
		// Get base upload path
		$uploads = wp_upload_dir();
		// Get / make directory path
		$path = trailingslashit( $uploads['basedir'] ) . '/logs';
		// Make sure the directory exists
		if( !file_exists( $path ) ) {
			mkdir( $path, 0755, true );
		}
		// Append the file name
		$path = $path . '/dev.log';
		// Make sure the file exists, and create if it doesn't
		if( !file_exists( $path ) ) {
			error_log( '', 3, $path );
		}
		return $path;
	}

	public static function log_error( $e ) {
		$d = new \DateTime();
		$message  = '================================================================================' . "\n";
		$message .= 'Error : ' . $e->getMessage() . "\n";
		$message .= 'File  : ' . $e->getFile() . "\n";
		$message .= 'Line  : ' . $e->getLine() . "\n";
		$message .= 'Time  : ' . $d->format( 'Y-m-d h:i:s' ) . "\n";
		error_log( $message, 3, self::get_log_path() );
	}

	public static function get_debug_status() {
		return array(
			'WP_DEBUG'         => defined( 'WP_DEBUG' ) && WP_DEBUG === true ? 'Enabled' : 'Disabled',
			'WP_DEBUG_LOG'     => defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG === true ? 'Enabled' : 'Disabled',
			'WP_DEBUG_DISPLAY' => defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY === true ? 'Enabled' : 'Disabled',
			'SCRIPT_DEBUG'     => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ? 'Enabled' : 'Disabled',
			'SAVEQUERIES'      => defined( 'SAVEQUERIES' ) && SAVEQUERIES === true ? 'Enabled' : 'Disabled',
		);
	}

}