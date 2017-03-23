<?php

namespace Mpress;

class Theme {

	/**
	 * Instances
	 * @since 1.0.0
	 * @access protected
	 * @var (array) $instances : Collection of instantiated classes
	 */
	protected static $instances = array();
	protected static $theme_path;
	protected static $theme_uri;
	protected static $theme_name;
	protected static $theme_version;

	/**
	 * Constructor
	 * Though shall not construct that which cannot be constructed
	 * @access private
	 */
	protected function __construct() {
		// Nothing to do here...
	}

	/**
	 * Get instance
	 * Gets single instance of called class
	 * Insures only a single instance of a class is created (singleton)
	 * @since 1.0.0
	 * @return (object) $instance : Single instance of called class
	 */
	public static function get_instance( $class = '' ) {
		// Use late static binding to get called class if one isn't passed in
		$class = !empty( $class ) ? $class : get_called_class();
		// Check for existing instance, and set it if needed
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
		$this->set_modals();
	}

	private function set_fields() {
		self::$theme_path    = trailingslashit( get_template_directory() );
		self::$theme_uri     = trailingslashit( get_template_directory_uri() );
		self::$theme_name    = 'mpress';
		self::$theme_version = '2.0.0';
	}

	private function set_modals() {
		// Get child modals...
		$child_modals = apply_filters( 'mpress_theme_modules', array() );
		// $child_modals = array();
		// Merge with core modals
		$modals = array_merge( $child_modals, array(
			'\\Mpress\\Models\\Styles'     => null,
			'\\Mpress\\Models\\Scripts'    => null,
			'\\Mpress\\Models\\Menus'      => null,
			'\\Mpress\\Models\\Comments'   => null,
			'\\Mpress\\Models\\Customizer' => null,
			'\\Mpress\\Models\\Setup'      => null,
			'\\Mpress\\Models\\Editor'     => null,
			'\\Mpress\\Models\\Header'     => null,
			'\\Mpress\\Models\\Background' => null,
			'\\Mpress\\Models\\Social'     => null,
			'\\Mpress\\Models\\Icons'      => null,
			'\\Mpress\\Models\\Media'      => null,
			'\\Mpress\\Models\\Setup'      => null,
			'\\Mpress\\Models\\Sidebars'   => null,
			'\\Mpress\\Models\\Meta'       => null,
			'\\Mpress\\Models\\Hooks'      => null,
		) );
		// Register each modules as necessary
		foreach( $modals as $modal => $path ) {
			// Include if necessary
			if( !empty( $path ) ) {
				include_once $path;
			}
			// Create instance of modules
			self::$instances[$modal] = $modal::get_instance();
		}
	}

} // end class