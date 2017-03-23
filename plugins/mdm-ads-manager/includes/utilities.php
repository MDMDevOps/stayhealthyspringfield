<?php

namespace Mdm\AdsManager;

class Utilities extends \Mdm\AdsManager {

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

	public static function get_post_meta( $post_id ) {
		// Set up some default args
		$box_meta = array();
		// Get the post meta
		$post_meta = get_post_meta( $post_id );
		if( $post_meta !== false ) {
			// Clean up the meta for use in our callback
			foreach( $post_meta as $name => $value ) {
				$box_meta[$name] = current( $value );
			}
		}
		return $box_meta;
	}

	public static function get_nonce( $name = '' ) {
		return array(
			'nonce' => sprintf( '%s_nonce', $name ),
			'key'   => sprintf( '%s_nonce_key', $name ),
		);
	}

	public static function get_ancestors_count( $id, $type, $group ) {
		$ancestors = get_ancestors( $id, $type, $group );
		return count( $ancestors );

	}

}