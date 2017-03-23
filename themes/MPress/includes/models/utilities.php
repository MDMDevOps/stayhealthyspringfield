<?php

/**
 * Utilities Functions
 * Static functions we can include / use throughout the theme
 * @since 2.0.0
 * @see https://codex.wordpress.org/Custom_Headers
 * @todo document module, add link here to wiki page
 * @package mpress
 */
namespace Mpress\Models;

class Utilities extends \Mpress\Theme {

	/**
	 * Helper function to use relative URLs
	 * @since 1.0.0
	 */
	public static function uri( $uri = '' ) {
		return parent::$theme_uri . ltrim( $uri, '/' );
	}

	/**
	 * Helper function to use relative paths
	 * @since 1.0.0
	 */
	public static function path( $path = '' ) {
		return parent::$theme_path . ltrim( $path, '/' );
	}
}