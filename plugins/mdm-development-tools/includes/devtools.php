<?php

namespace Mdm;

class DevTools {

	/**
	 * Instances
	 * @since 1.0.0
	 * @access protected
	 * @var (array) $instances : Collection of instantiated classes
	 */
	protected static $instances = array();

	/**
	 * Plugin Path
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_path : The path to the plugins location on the server, is inherited by child classes
	 */
	protected static $plugin_path;

	/**
	 * Plugin URL
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_url : The URL path to the location on the web, accessible by a browser
	 */
	protected static $plugin_url;

	/**
	 * Plugin File
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_file : The reference to the core plugin file, used by Wordpress to build the slug
	 */
	protected static $plugin_file;

	/**
	 * Plugin Slug
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_slug : Basename of the plugin, needed for Wordpress to set transients, and udpates
	 */
	protected static $plugin_slug;

	/**
	 * Plugin Name
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_name : The unique identifier for this plugin
	 */
	protected static $plugin_name;
	protected static $plugin_wp_name;


	/**
	 * Plugin Version
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_version : The version number of the plugin, used to version scripts / styles
	 */
	protected static $plugin_version;

	/**
	 * Plugin Options
	 * @since 1.0.0
	 * @access protected
	 * @var (array) $plugin_options : The array that holds plugin options
	 */
	protected static $plugin_settings;

	/**
	 * Constructor
	 * Though shall not construct that which cannot be constructed
	 * @access private
	 */
	protected function __construct() {
		// Nothing to do here right now
	}

	/**
	 * Get instance
	 * Gets single instance of called class
	 * Insures only a single instance of a class is created (singleton)
	 * @since 1.0.0
	 * @return (object) $instance : Single instance of called class
	 */
	public static function get_instance( ) {
		// Use late static binding to get called class
		$class = get_called_class();
		if ( !isset(self::$instances[$class] ) ) {
			self::$instances[$class] = new $class();
		}
		return self::$instances[$class];
	}

	/**
	 * Organize operation of the plugin
	 */
	public function burn_baby_burn() {
		$this->set_fields();
		$this->register_settings_hooks();
		$this->register_admin_hooks();
		$this->register_git_hooks();
		$this->register_upgrade_hooks();
	}

	private function set_fields() {
		self::$plugin_path = plugin_dir_path( __DIR__ );
		self::$plugin_file = self::$plugin_path . 'index.php';
		self::$plugin_url  = plugin_dir_url( __DIR__ );
		self::$plugin_slug = plugin_basename( self::$plugin_file );
		self::$plugin_name = 'mdm_devtools';
		self::$plugin_version = '1.0.2';
	}

	private function register_settings_hooks() {
		$module = \Mdm\DevTools\Modules\Settings::get_instance();
		add_action( 'init', array( $module, 'set_fields' ) );
		add_action( 'admin_init', array( $module, 'register_settings' ) );
		add_filter( sprintf( '%s_admin_page_tabs', self::$plugin_name ), array( $module, 'get_page_tabs' ), 10, 1 );
	}

	private function register_admin_hooks() {
		$module = \Mdm\DevTools\Modules\Admin::get_instance();
		add_action( 'admin_enqueue_scripts', array( $module, 'register_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $module, 'enqueue_assets' ) );
		add_action( 'admin_menu', array( $module, 'add_admin_pages' ) );
	}

	private function register_git_hooks() {
		$module = \Mdm\DevTools\Modules\Git::get_instance();
		add_action( 'rest_api_init',array( $module, 'register_rest_route' ) );
		add_filter( 'repository_data', array( $module, 'get_repository_data' ), 10, 2 );
		add_action( 'wp_ajax_git_terminus', array( $module, 'git_terminus' ) );
		add_action( 'display_git_configuration', array( $module, 'display_git_config' ) );
	}

	private function register_upgrade_hooks() {
		$module = \Mdm\DevTools\Modules\Upgrade::get_instance();
		add_action( 'admin_init', array( $module, 'set_fields' ) );
		add_filter( 'pre_set_site_transient_update_plugins', array( $module, 'modify_transient' ), 10, 1 );
		add_filter( 'upgrader_post_install', array( $module, 'after_install' ), 10, 3  );
		add_filter( 'plugins_api', array( $module, 'plugin_popup' ), 10, 3 );
	}

} // end class