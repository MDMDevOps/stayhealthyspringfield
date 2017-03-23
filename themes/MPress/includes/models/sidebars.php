<?php

/**
 * Sidebars
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Sidebars extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
	}

	private function register_actions() {
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	public function register_sidebars() {
		$sidebars = array(
			'primary' => array(
				'name'          => __( 'Primary Sidebar', 'mpress' ),
				'id'            => 'primary-sidebar',
				'before_widget' => '<div id="%1$s" class="widget group %2$s">',
				'after_widget'  => "</div>",
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			),
			'404' => array(
				'name'          => __( '404 Content Area', 'mpress' ),
				'id'            => '404-content-widgets',
				'before_widget' => '<div id="%1$s" class="widget group %2$s">',
				'after_widget'  => "</div>",
				'before_title'  => '<h2 class="not-found-title widget-title">',
				'after_title'   => '</h2>',
			)
		);
		// Apply filters to allow child themes to extend / override
		$sidebars = apply_filters( 'mpress_sidebars', $sidebars );
		// register each sidebar
		foreach( $sidebars as $name => $sidebar ) {
			register_sidebar( $sidebar );
		}
	}
}