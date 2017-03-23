<?php
/**
 * Update plugin from private github repo
 *
 * @since   1.0.0
 * @link    http://midwestfamilymarketing.com
 * @package mdm_show_manager
 * @license GPL-2.0+
 *
 */
// Prevent loading this file directly and/or if the class is already defined
if ( ! defined( 'ABSPATH' ) || class_exists( 'WPGitHubUpdater' ) || class_exists( 'WP_GitHub_Updater' ) ) {
    return;
}
class Mpress_Theme_Updater extends Mpress_Theme_Engine implements Mpress_Theme_Module {

    private static $response;
    const USERNAME = 'MDMDevOps';
    const REPOSITORY = 'MPress';
    /**
     * Run the module
     * Defines exactly how the module should be setup, and kickoff operations
     * @since 2.0.0
     */
    public function ignite() {
    	// If this theme is directly activated, we can bail
    	if( self::get_active_theme() === parent::$theme_name ) {
    	    return false;
    	}
    	// add_action( 'admin_init', array( 'Mpress_Theme_Updater', 'set_plugin_properties') );
    	add_filter( 'pre_set_site_transient_update_themes', array( 'Mpress_Theme_Updater', 'modify_transient'), 10, 1 );
    	add_filter( 'upgrader_post_install', array( 'Mpress_Theme_Updater', 'fix_install_folder'), 10, 3  );
    	// add_filter( 'themes_api', array( 'Mpress_Theme_Updater', 'theme_popup'), 10, 3 );
    }

    private static function get_active_theme() {
        $theme = wp_get_theme();
        return $theme->name;
    }

    /**
     * Get authorization key from database
     */
    public static function authorize() {
        $auth = get_option( 'mdm_github_api_key' );
        if( !empty( $auth ) ) {
        	return $auth;
        }
        return false;
    }
    /**
     * Get repository data From Github
     * @since 1.0.0
     */
    public static function get_repository_data() {
        if( self::authorize() === false ) {
            return false;
        }
        $update = false;
            // 1.1: Build request URI
            $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', self::USERNAME, self::REPOSITORY );
            // 1.2: Append access token to URI
            $request_uri = add_query_arg( 'access_token', self::authorize(), $request_uri );
            // 1.3: Get json response from github and parse it
            $response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true );
            // 1.4: If we didn't get a good response, we can bail
            if( !is_array( $response ) || !isset( $response[0]['id'] ) ) {
                return false;
            }
            // Try to get response for current major release
            foreach( $response as $release ) {
            	if( intval( $release['tag_name'] ) === intval( parent::$theme_version ) ) {
            		$update = $release;
            		$update['zipball_url'] = add_query_arg( 'access_token', self::authorize(), $release['zipball_url'] );
            	}
            	break;
            }
          	// Give response back...
            return $update;
    }
    public static function modify_transient( $transient ) {
    	// Check if transient has a checked property, else we can bail...
    	if( !is_object( $transient ) || !property_exists( $transient, 'checked') ) {
    	   return $transient;
    	}
    	// Check if we have a response object
    	if( is_null( self::$response ) ) {
    		self::$response = self::get_repository_data();
    	}
    	// If response is false (not authorized) we can bail
    	if( self::$response === false ) {
    		return $transient;
    	}

    	// Set theme
    	$theme = wp_get_theme( basename( parent::$theme_path ) );
        //If the version on github isn't a higher version, we can go ahead and bail...
        if( version_compare( self::$response['tag_name'], $transient->checked[ basename( parent::$theme_path ) ], '>' ) === false ) {
            return $transient;
        }
        // If we made it here, we can begin the update process
        $update = array(
            'url'         => $theme->get( 'ThemeURI' ),
            'theme'        => basename( parent::$theme_path ),
            'package'     => self::$response['zipball_url'],
            'new_version' => self::$response['tag_name']
        );
        $transient->response[ basename( parent::$theme_path ) ] = $update;

        return $transient; // Return filtered transient
    }
    // public function theme_popup( $result, $action, $args ) {
    // 	var_dump($args);
    //     if( !empty( $args->slug ) ) { // If there is a slug
    //         if( $args->slug == basename( parent::$theme_path ) ) { // And it's our slug
    //             if( is_null( self::$response ) ) {
    //             	self::$response = self::get_repository_data();
    //             }
    //             // Set it to an array
    //             $plugin = array(
    //                 'name'              => 'asdfasdf',
    //                 'slug'              => 'MPress',
    //                 'version'           => self::$response['tag_name'],
    //                 'author'            => 'asdfasdf',
    //                 'author_profile'    => 'asdfasdf',
    //                 'last_updated'      => 'asdfasdf',
    //                 'homepage'          => 'asdfasdf',
    //                 'short_description' => 'asdfasdf',
    //                 'sections'          => array(
    //                     'Description'   => 'asdfasdf',
    //                     'Updates'       => self::$response['body'],
    //                 ),
    //                 'download_link'     => self::$response['zipball_url'],
    //                 'action'            => $action
    //             );
    //             // $plugin = array(
    //             //     'name'              => $this->plugin["Name"],
    //             //     'slug'              => $this->plugin_slug,
    //             //     'version'           => self::$response['tag_name'],
    //             //     'author'            => $this->plugin["AuthorName"],
    //             //     'author_profile'    => $this->plugin["AuthorURI"],
    //             //     'last_updated'      => self::$response['published_at'],
    //             //     'homepage'          => $this->plugin["PluginURI"],
    //             //     'short_description' => $this->plugin["Description"],
    //             //     'sections'          => array(
    //             //         'Description'   => $this->plugin["Description"],
    //             //         'Updates'       => self::$response['body'],
    //             //     ),
    //             //     'download_link'     => self::$response['zipball_url'],
    //             //     'action'            => $action
    //             // );
    //             return (object) $plugin; // Return the data
    //         }
    //     }
    //     return $result; // Otherwise return default
    // }
    public static function fix_install_folder( $true, $hook_extra, $result ){
    	global $wp_filesystem;
    	$proper_destination = trailingslashit( $result['local_destination'] ) . $hook_extra['theme'];
    	$wp_filesystem->move( $result['destination'], $proper_destination );
    	if( get_option( 'theme_switched' ) == $hook_extra['theme'] && $result['destination_name'] == get_stylesheet() ){
    		wp_clean_themes_cache();
    		switch_theme( $hook_extra['theme'] );
    	}
    	$result['destination'] = $proper_destination;
    	$result['destination_name'] = $hook_extra['theme'];
    	return $true;
    }
}