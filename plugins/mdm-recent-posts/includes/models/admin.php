<?php

namespace Mdm\Recent_Posts\Models;

use \Mdm\Recent_Posts\Models\Utilities as Utilities;

class Admin extends \Mdm\Recent_Posts {

	/**
	 * Register Styles
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
	 */
	public function register_assets() {
		// Register css
		wp_register_style( sprintf( '%s_admin',  parent::$plugin_name ), Utilities::url( 'styles/dist/admin.min.css' ), array(),  parent::$plugin_version, 'all' );
		// Register javascript
		wp_register_script( sprintf( '%s_admin', parent::$plugin_name ), Utilities::url( 'scripts/dist/admin.min.js' ), array( 'jquery' ), parent::$plugin_version, true );

	}

	public function enqueue_assets() {
		// If function is undefined, we need to bail
		if( !function_exists( 'get_current_screen' ) ) {
			return;
		}
		// Get current screen so we can check if we're on the widget page
		$page = get_current_screen();
		// Conditionally enqueue scripts and styles needed for widget page
		if( $page->id === 'widgets' ) {
			// Enqueue our styles and scripts
			wp_enqueue_style( sprintf( '%s_admin',  parent::$plugin_name ) );
			wp_enqueue_script( sprintf( '%s_admin',  parent::$plugin_name ) );
		}
	}
}