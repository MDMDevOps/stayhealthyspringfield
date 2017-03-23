<?php

/**
 * Menus
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Menus extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
		$this->register_shortcodes();
	}

	private function register_actions() {
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
	}

	private function register_shortcodes() {
		add_shortcode( 'embed_nav_menu', array( $this, 'get_menu' ) );
	}

	public function register_menus() {
		$menus = apply_filters( 'mpress_menus', array(
				'primary-navbar'  => __( 'Primary Nav Bar', 'mpress' ),
				'off-canvas-nav'  => __( 'Off Canvas Menu', 'mpress' ),
				'footer-navbar'   => __( 'Footer Nav Bar', 'mpress' ),
			)
		);
		register_nav_menus( $menus );
	}

	public function get_menu( $atts = array() ) {
		// If no menu location, or menu is specified, we can bail without doing anything
		if( !$atts['theme_location'] && !$atts['menu'] ) {
			return false;
		}
		// Merge passed in atts with defaults
		$atts = shortcode_atts( array(
			'theme_location'  => '',
			'menu'            => '',
			'container'       => 'div',
			'container_class' => '',
			'container_id'    => '',
			'menu_class'      => 'menu',
			'menu_id'         => '',
			'echo'            => false,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
			), $atts, 'mpress_embed_menu' );
			// !important Force echo to false
			$atts['echo'] = false;
		return wp_nav_menu( $atts );
	}
}