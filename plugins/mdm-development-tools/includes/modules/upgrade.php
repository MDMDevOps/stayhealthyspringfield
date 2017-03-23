<?php
/**
 * Update plugin from private github repo
 *
 * @since   1.0.0
 * @link    http://midwestfamilymarketing.com
 * @package mdm_devtools
 * @license GPL-2.0+
 *
 */
namespace Mdm\DevTools\Modules;

use \Mdm\DevTools\Modules\Utilities as Utilities;

// Prevent loading this file directly and/or if the class is already defined
if ( ! defined( 'ABSPATH' ) || class_exists( 'WPGitHubUpdater' ) || class_exists( 'WP_GitHub_Updater' ) ) {
	return;
}

class Upgrade extends \Mdm\Devtools {

	/**
	 * RESPONSE
	 * @since 1.0.0
	 * @access private
	 * @var
	 */
	private $github_data;

	/**
	 * RESPONSE
	 * @since 1.0.0
	 * @access private
	 * @var
	 */
	private $plugin_data;

	/**
	 * RESPONSE
	 * @since 1.0.0
	 * @access private
	 * @var
	 */
	private $is_active;

	/**
	 * RESPONSE
	 * @since 1.0.0
	 * @access private
	 * @var
	 */
	private $auth_key;

	/**
	 * RESPONSE
	 * @since 1.0.0
	 * @access private
	 * @var
	 */
	const USERNAME = 'MDMDevOps';

	/**
	 * RESPONSE
	 * @since 1.0.0
	 * @access private
	 * @var
	 */
	const REPOSITORY = 'mdm-development-tools';

	/**
	 * Run the module
	 * Defines exactly how the module should be setup, and kickoff operations
	 * @since 2.0.0
	 */
	public function burn_baby_burn() {
		add_action( 'admin_init', array( 'Mpm_Data_Connector_Updater', 'set_plugin_properties' ) );
		add_filter( 'pre_set_site_transient_update_plugins', array( 'Mpm_Data_Connector_Updater', 'modify_transient' ), 10, 1 );
		add_filter( 'upgrader_post_install', array( 'Mpm_Data_Connector_Updater', 'after_install' ), 10, 3  );
		add_filter( 'plugins_api', array( 'Mpm_Data_Connector_Updater', 'plugin_popup' ), 10, 3 );
	}

	public function set_fields() {
		$this->auth_key    = parent::$plugin_settings['api_key'];
		$this->plugin_data = get_plugin_data( parent::$plugin_file );
		$this->is_active = is_plugin_active( parent::$plugin_slug );
	}

	/**
	 * Get authorization key from database
	 */
	public function authorize() {
		$auth = parent::$plugin_settings['api_key'];
		if( empty( $auth ) ) {
			return false;
		}
		return $auth;
	}
	/**
	 * Get repository data From Github
	 * @since 1.0.0
	 */
	public function get_github_data() {
		// Instantiate update variable
		$github_data = false;
		// If we have an auth key...
		if( !empty( $this->auth_key ) ) {
			// Build request URI
			$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', self::USERNAME, self::REPOSITORY );
			// Append access token to URI
			$request_uri = add_query_arg( 'access_token', $this->auth_key, $request_uri );
			// Get json response from github and parse it
			$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true );
			// Make sure we have a good response
			if( is_array( $response ) && isset( $response[0]['id'] ) ) {
				$github_data = current( $response );
				$github_data['zipball_url'] = add_query_arg( 'access_token', $this->auth_key, $github_data['zipball_url'] );
			}
		}
		return $github_data;
	}

	/**
	 * Modify Transient data
	 */
	public function modify_transient( $transient ) {
		// Check if transient has a checked property, else we can bail...
		if( !is_object( $transient ) || !property_exists( $transient, 'checked') ) {
			return $transient;
		}
		// Check if we have a response object
		if( empty( $this->github_data ) ) {
			$this->github_data = $this->get_github_data();
		}
		// If response is false (not authorized / bad response) we can bail
		if( $this->github_data === false ) {
			return $transient;
		}
		// If the version on github isn't a higher version, we can go ahead and bail...
		if( version_compare( $this->github_data['tag_name'], $transient->checked[ parent::$plugin_slug ], '>' ) === false ) {
			return $transient;
		}
		// If we made it here, we can begin the update process
		$update = array(
			'url' => $this->plugin_data['PluginURI'],
			'slug' => current( explode( '/', parent::$plugin_slug ) ),
			'plugin' => parent::$plugin_slug,
			'package' => $this->github_data['zipball_url'],
			'new_version' => $this->github_data['tag_name'],
		);
		$transient->response[ parent::$plugin_slug ] = (object)$update;
		// Utilities::expose( $transient );
		return $transient; // Return filtered transient
	}

	/**
	 * Plugin popup when you click the data button thingy
	 */
	public function plugin_popup( $result, $action, $args ) {
		if( !empty( $args->slug ) ) { // If there is a slug
			if( $args->slug == parent::$plugin_slug ) { // And it's our slug
				// Check if we have a response object
				if( empty( $this->github_data ) ) {
					$this->github_data = $this->get_github_data();
				}
				// If response is false (not authorized / bad response) we can bail
				if( $this->github_data !== false ) {
					// Set it to an array
					$plugin = array(
						'name'              => $this->$plugin_data["Name"],
						'slug'              => parent::$plugin_slug,
						'version'           => $this->github_data['tag_name'],
						'author'            => $this->$plugin_data["AuthorName"],
						'author_profile'    => $this->$plugin_data["AuthorURI"],
						'last_updated'      => $this->github_data['published_at'],
						'homepage'          => $this->$plugin_data["PluginURI"],
						'short_description' => $this->$plugin_data["Description"],
						'sections'          => array(
							'Description'   => $this->$plugin_data["Description"],
							'Updates'       => $this->github_data['body'],
						),
						'download_link'     => $this->github_data['zipball_url'],
						'action'            => $action,
					);
					return (object)$plugin; // Return the data
				}
			}
		}
		return $result; // Otherwise return default
	}
	public function after_install( $response, $hook_extra, $result ) {
		// 1: Get the global filesystem object
		global $wp_filesystem;
		// 2: Get the plugin directory path
		$install_directory = plugin_dir_path( parent::$plugin_file );
		// 3: Move files to the plugin
		$wp_filesystem->move( $result['destination'], $install_directory );
		// 4. Set the destination for the rest of the stack
		$result['destination'] = $install_directory;
		// 5. If it was active, re-activate
		if ( $this->is_active ) {
			activate_plugin( parent::$plugin_slug );
		}
		return $result;
	}
}