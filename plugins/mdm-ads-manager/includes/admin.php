<?php

namespace Mdm\AdsManager;

use \Mdm\AdsManager\Utilities as Utilities;

class Admin extends \Mdm\AdsManager {

	private $metaboxes = array();

	public function register_assets() {
		wp_register_style( sprintf( '%s_admin',  parent::$plugin_name ), Utilities::url( 'styles/dist/admin.min.css' ), array(),  parent::$plugin_version, 'all' );
		wp_register_script( sprintf( '%s_admin', parent::$plugin_name ), Utilities::url( 'scripts/dist/admin.min.js' ), array( 'jquery' ), parent::$plugin_version, true );
	}

	/**
	 * Enqueue admin side assets
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 * @see https://developer.wordpress.org/reference/functions/wp_localize_script/
	 */
	public function enqueue_assets() {
		wp_enqueue_style( sprintf( '%s_admin',  parent::$plugin_name ) );
		wp_enqueue_script( sprintf( '%s_admin',  parent::$plugin_name ) );
		wp_localize_script( sprintf( '%s_admin', parent::$plugin_name ), parent::$plugin_name, array( 'wpajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function add_admin_pages() {
		// add_menu_page( __( 'Ads Manager', parent::$plugin_name ), __( 'Ads Manager', parent::$plugin_name ), 'manage_options', parent::$plugin_name, array( $this, 'display_admin_page' ), 'dashicons-chart-area', 85 );
		// add_submenu_page( parent::$plugin_name, __( 'Ad Units', parent::$plugin_name ), __( 'Ad Units', parent::$plugin_name ), 'manage_options', 'edit.php?post_type=adunit', false );
		// add_submenu_page( parent::$plugin_name, __( 'Ad Groups', parent::$plugin_name ), __( 'Ad Groups', parent::$plugin_name ), 'manage_options', 'edit-tags.php?taxonomy=adgroup', false );
		// add_submenu_page( parent::$plugin_name, __( 'Help', parent::$plugin_name ), __( 'Help', parent::$plugin_name ), 'manage_options', parent::$plugin_name . '_help', array( $this, 'display_help_page' ) );
	}

	public function adgroup_parent_file( $parent_file ) {
		if ( get_current_screen()->taxonomy === 'adgroup' ) {
			$parent_file = parent::$plugin_name;
		}
		return $parent_file;
	}

	public function display_admin_page() {
		echo '<h1>Admin Page</h1>';
	}

	public function display_help_page() {

	}

}