<?php

/**
 * Mpress Icon Fonts
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Icons extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
		$this->register_shortcodes();
		$this->register_filters();
	}

	/**
	 * Register action hooks w/ WordPress core
	 * @since 2.0.0
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
	 */
	private function register_actions() {
		add_action( 'mpress_icon', array( $this, 'output_icon' ) );
	}

	private function register_shortcodes() {
		add_shortcode( 'mpress_icon', array( $this, 'get_icon' ) );
	}

	private function register_filters() {
		add_filter( 'get_mpress_icon', array( $this, 'get_icon' ) );
	}

	private function get_icon_class( $icon_name = null ) {
		// Define preset icons
		$icons = array(
		    'twitter'         => 'icon fa fa-twitter',
		    'facebook'        => 'icon fa fa-facebook',
		    'googleplus'      => 'icon fa fa-google-plus',
		    'youtube'         => 'icon fa fa-youtube',
		    'pinterest'       => 'icon fa fa-pinterest',
		    'linkedin'        => 'icon fa fa-linkedin',
		    'author'          => 'icon fa fa-user',
		    'time'            => 'icon fa fa-clock-o',
		    'category'        => 'icon fa fa-tags',
		    'tag'             => 'icon fa fa-hashtag',
		    'comment'         => 'icon fa fa-comments',
		    'edit'            => 'icon fa fa-pencil',
		    'home'            => 'icon fa fa-home',
		    'toggle-down'     => 'icon fa fa-chevron-down',
		    'toggle-sub-menu' => 'icon fa fa-chevron-down',
		    'menu'            => 'icon fa fa-bars',
		);
		// Allow child themes / plugins / etc to manipulate icon classes
		$icons = apply_filters( 'mpress_font_icons', $icons );
		// If no icon request is passed in, just bail
		if( !$icon_name ) {
		    return false;
		}
		// If icon exists in our map, return it
		if( isset( $icons[ strtolower( $icon_name ) ] ) ) {
		    return esc_attr( $icons[ strtolower( $icon_name ) ] );
		}
		// Else return span with string as the class
		else {
			return 'icon ' . trim( esc_attr( $icon_name ) );
		}
	}

	public function get_icon( $args = null ) {
		// Setup array, if string passed in
		if( !is_array( $args ) ) {
			$args = array(
				'name' => $args,
				'type' => 'span',
			);
		}
		$defaults = array(
			'name' => '',
			'type' => 'span',
		);
		$args = wp_parse_args( $args, $defaults );
		// If no icon request is passed in, just bail
		if( empty( $args['name'] ) ) {
		    return false;
		}
		return sprintf( '<%1$s class="%2$s" aria-hidden="true"></%1$s>',  trim( $args['type'] ), $this->get_icon_class( $args['name'] ) );
	}

	public function output_icon( $args = null ) {
		echo $this->get_icon( $args );
	}
}