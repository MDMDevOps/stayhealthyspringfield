<?php

namespace Mdm;

class RssDisplay {

	/**
	 * Plugin Name
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_name : The unique identifier for this plugin
	 */
	protected static $plugin_name = 'mdm_rss_display';

	/**
	 * Plugin Version
	 * @since 1.0.0
	 * @access protected
	 * @var (string) $plugin_version : The version number of the plugin, used to version scripts / styles
	 */
	protected static $plugin_version = '1.0.0';

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
		$this->register_display_hooks();
		$this->register_widget_hooks();
	}

	private function set_fields() {
		self::$plugin_path = plugin_dir_path( __DIR__ );
		self::$plugin_file = self::$plugin_path . 'index.php';
		self::$plugin_url  = plugin_dir_url( __DIR__ );
		self::$plugin_slug = plugin_basename( self::$plugin_file );
	}

	private function register_display_hooks() {
		//display_adgroup
		$module = \Mdm\RssDisplay\Display::get_instance();
		add_shortcode( 'display_feed', array( $module, 'display_feed_shortcode' ) );
	}

	private function register_widget_hooks() {
		$module = \Mdm\RssDisplay\Widgets::get_instance();
		add_action( 'widgets_init', array( $module, 'register' ) );
	}

} // end class