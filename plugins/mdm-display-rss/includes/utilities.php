<?php

namespace Mdm\RssDisplay;

class Utilities extends \Mdm\RssDisplay {

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
}