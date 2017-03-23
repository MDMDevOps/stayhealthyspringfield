<?php

namespace Mdm\DevTools\Modules;

use \Mdm\DevTools\Modules\Utilities as Utilities;

class Settings extends \Mdm\Devtools {

	private $sections;
	private $fields;
	private $settings;

	/**
	 * Kickoff operation of this module
	 */
	public function burn_baby_burn() {
		// Configure the fields we need
		$this->sections = $this->get_sections();
		$this->fields   = $this->get_fields();
		$this->register_actions();
		$this->register_filters();
	}

	public function set_fields() {
		$this->sections = $this->get_sections();
		$this->fields   = $this->get_fields();
		parent::$plugin_settings = $this->get_settings();
	}

	private function register_actions() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	private function register_filters() {
		add_filter( sprintf( '%s_admin_page_tabs', parent::$plugin_name ), array( $this, 'get_page_tabs' ), 10, 1 );
	}

	/**
	 * Get default settings
	 * @since 1.0.0
	 * @static
	 */
	private function get_default_settings() {
		$default_options = array();
		foreach( $this->get_fields() as $field => $args ) {
			$default_options[$field] = isset( $args['default'] ) ? $args['default'] : null;
		}
		return $default_options;
	}

	public function get_settings() {
		return wp_parse_args( get_option( parent::$plugin_name, array() ), $this->get_default_settings() );
	}

	/**
	 * Define setting page sections (tabs)
	 * @since 1.0.0
	 * @access private
	 */
	private function get_sections( $current = null, $key = null ) {

		$sections = array(
			sprintf( '%s_%s', parent::$plugin_name, 'settings' ) => array(
				'title'   => __( 'Plugin Settings', parent::$plugin_name ),
				'tab'     => __( 'Settings', parent::$plugin_name ),
				'content' => 'views/settings.php',
				'description' => __( "Enter your github credentials, and repository data here. You'll need a <em>Personal Access Token</em> from github in order to access private repositories from within this plugin.", parent::$plugin_name ),
				'key'     => sprintf( '%s_%s', parent::$plugin_name, 'settings' ),
			),
		);

		if( !is_null( $current ) ) {
			if( !is_null( $key ) ) {
				return $sections[$current][$key];
			}
			return $sections[$current];
		}
		return $sections;

	}

	public function get_page_tabs( $tabs = array() ) {
		return array_merge( $tabs, $this->get_sections() );
	}

	/**
	 * Get settings fields
	 * @since 1.0.0
	 * @access private
	 */
	private function get_fields() {
		$fields = array(
			'api_key'     => array(
			   'title'       => __( 'API Key', parent::$plugin_name ),
			   'label'       => null,
			   'type'        => 'text',
			   'field_class' => 'widefat form-control',
			   'field_id'    => sprintf( '%s[api_key]', parent::$plugin_name ),
			   'section'     => sprintf( '%s_%s', parent::$plugin_name, 'settings' ),
			   'description' => sprintf( '%s%s %s%s', __( 'Enter your GitHub', parent::$plugin_name ), '<a href="https://github.com/blog/1509-personal-api-tokens" target="_blank">', __( 'Personal Access Token', parent::$plugin_name ), '</a>' ),
			   'placeholder' => null,
			),
			'git_user'     => array(
			   'title'       => __( 'Github User Name', parent::$plugin_name ),
			   'label'       => null,
			   'type'        => 'text',
			   'field_class' => 'widefat form-control',
			   'field_id'    => sprintf( '%s[git_user]', parent::$plugin_name ),
			   'section'     => sprintf( '%s_%s', parent::$plugin_name, 'settings' ),
			   'description' => __( 'The user or organization that the github repository belongs to', parent::$plugin_name ),
			   'default'     => 'MDMDevOps',
			   'placeholder' => null,
			),
			'git_repo'     => array(
			   'title'       => __( 'Github Repo Name', parent::$plugin_name ),
			   'label'       => null,
			   'type'        => 'text',
			   'field_class' => 'widefat form-control',
			   'field_id'    => sprintf( '%s[git_repo]', parent::$plugin_name ),
			   'section'     => sprintf( '%s_%s', parent::$plugin_name, 'settings' ),
			   'description' => __( 'The name of the github repository', parent::$plugin_name ),
			   'placeholder' => null,
			),
			'git_path'     => array(
			   'title'       => __( 'Local Repo Path', parent::$plugin_name ),
			   'label'       => null,
			   'type'        => 'text',
			   'field_class' => 'widefat form-control',
			   'field_id'    => sprintf( '%s[git_path]', parent::$plugin_name ),
			   'section'     => sprintf( '%s_%s', parent::$plugin_name, 'settings' ),
			   'description' => __( 'The path to the git repository, relative to the root WordPress install', parent::$plugin_name ),
			   'default'     => 'wp-content',
			   'placeholder' => null,
			),
			'cache_busting'  => array(
			   'title'       => __( 'Cache Buster', parent::$plugin_name ),
			   'label'       => null,
			   'type'        => 'checkbox',
			   'field_class' => 'widefat form-control',
			   'field_id'    => sprintf( '%s[cache_busting]', parent::$plugin_name ),
			   'section'     => sprintf( '%s_%s', parent::$plugin_name, 'settings' ),
			   'description' => __( 'Enable cache busting for scripts and styles?', parent::$plugin_name ),
			   'checked'     => 'on',
			   'placeholder' => null,
			),
		);
		return $fields;
	}

	/**
	 * Register the settings page
	 * @see https://developer.wordpress.org/reference/functions/add_options_page/
	 * @since 1.0.0
	 */
	public function register_page() {
		add_options_page( __( 'MPM Development Tools', parent::$plugin_name ), __( 'MPM Development Tools', parent::$plugin_name ), 'manage_options', parent::$plugin_name, array( $this, 'display_page' ) );
		add_submenu_page(
		    'tools.php',
		    __( 'MPM Development Tools', parent::$plugin_name ),
		    __( 'MPM Development Tools', parent::$plugin_name ),
		    'manage_options',
		    parent::$plugin_name,
		    array( $this, 'display_page' )
		);
	}

	/**
	 * Register plugin settings with WordPress
	 * @since 1.0.0
	 * @see https://codex.wordpress.org/Function_Reference/register_setting
	 * @see https://codex.wordpress.org/Function_Reference/add_settings_section
	 * @see https://codex.wordpress.org/Function_Reference/add_settings_field
	 */
	public function register_settings() {
		$sections = $this->get_sections();
		$fields   = $this->get_fields();
		$settings = $this->get_settings();
		// Add all setting sections ( as tabs )
		foreach( $sections as $section_name => $section_args ) {
			// if( $section['type'] === 'setting' ) {
				register_setting( $section_name, $section_name, array( $this, 'merge_settings' ) );
				add_settings_section( $section_name, $section_args['title'], array( $this, 'display_section' ), $section_name );
			// }
		}
	    // Cycle through fields, and register each
	    foreach( $fields as $name => $field ) {
	        // Construct some extra arguments
	        $field['id']      = sprintf( '%s[%s]', $field['section'], $name );
	        $field['value']   = $settings[$name];
	        // Add setting
	        add_settings_field( $name, $field['title'], array( $this, 'display_field' ), $field['section'], $field['section'], $field );
	    }
	}

	public function display_section( $section ) {
		echo '<hr>';
		echo $this->get_sections( $section['id'], 'description' );
	}

	public function merge_settings( $inputs ) {
		// Get plugin options
		$plugin_settings = get_option( parent::$plugin_name, array() );
		// Get defaults
		$default_settings = $this->get_default_settings();
		// Append passed in inputs to master plugin options array
		foreach( $default_settings as $key => $default_setting ) {
			$plugin_settings[ $key ] = isset( $inputs[$key] ) ? $inputs[$key] : $default_setting[$key];
		}
		// Update master plugin options
		update_option( parent::$plugin_name, $plugin_settings );
		return $inputs;
	}

	public function display_field( $field ) {
		// Display before text
		if( isset( $field['before'] ) && !empty( $field['before'] ) ) {
			echo $field['before'];
		}
		// Display appropriate input
		switch( $field['type'] ) {
			case 'text' :
				include parent::$plugin_path . 'partials/inputs/text.php';
				break;
			case 'url' :
				include parent::$plugin_path . 'partials/inputs/url.php';
				break;
			case 'number' :
				include parent::$plugin_path . 'partials/inputs/number.php';
				break;
			case 'email' :
				include parent::$plugin_path . 'partials/inputs/email.php';
				break;
			case 'checkbox' :
				include parent::$plugin_path . 'partials/inputs/checkbox.php';
				break;
			case 'radio' :
				include parent::$plugin_path . 'partials/inputs/radio.php';
				break;
			case 'password' :
				include parent::$plugin_path . 'partials/inputs/password.php';
				break;
			case 'textarea' :
				include parent::$plugin_path . 'partials/inputs/textarea.php';
				break;
			case 'select' :
				include parent::$plugin_path . 'partials/inputs/select.php';
				break;
			case 'group' : // Recursively call this function with single fields
				foreach( $field['fields'] as $single ) {
					self::display_field( $single );
				}
				break;
			default :
				break;
		}
		// Display after text
		if( isset( $field['after'] ) && !empty( $field['after'] ) ) {
			echo $field['after'];
		}
	}
}