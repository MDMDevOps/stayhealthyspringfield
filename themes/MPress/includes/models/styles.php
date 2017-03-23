<?php

/**
 * Style Function
 * @since 2.0.0
 * @see https://codex.wordpress.org/Custom_Headers
 * @todo document module, add link here to wiki page
 * @package mpress
 */
namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Styles extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
	}

	/**
	 * Register action hooks w/ WordPress core
	 * @since 1.0.0
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
	 */
	public function register_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_stylesheets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_stylesheets' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'register_login_stylesheets' ) );
		add_action( 'init', array( $this, 'register_editor_stylesheets' ) );
	}

	public function register_public_stylesheets() {
		$stylesheets = array(
			'FontAwesome' => array(
				'src'   => '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
				'deps'  => array(),
				'ver'   => '4.5.0',
				'media' => 'all',
			),
			'mpress-public' => array(
				'src'   =>  Utilities::uri( 'styles/dist/mpress.public.min.css' ),
				'deps'  => array( 'FontAwesome' ),
				'ver'   => '1.0.0',
				'media' => 'all',
			),
		);
		$stylesheets = $this->normalize_stylesheets( $stylesheets, 'mpress_public_stylesheets' );
		$this->register_stylesheets( $stylesheets );
	}

	public function register_admin_stylesheets() {
		$stylesheets = array(
			'mpress-admin' => array(
				'src' => Utilities::uri( 'styles/dist/mpress.admin.min.css' ),
				'deps' => array(),
				'ver' => '1.0.0',
				'media' => 'all',
			),
		);
		$stylesheets = $this->normalize_stylesheets( $stylesheets, 'mpress_admin_stylesheets' );
		$this->register_stylesheets( $stylesheets );
	}

	public function register_login_stylesheets() {
		$stylesheets = array();
		$stylesheets = $this->normalize_stylesheets( $stylesheets, 'mpress_login_stylesheets' );
		$this->register_stylesheets( $stylesheets );
	}

	public function register_editor_stylesheets() {
		// Define core editor styles
		$stylesheets = array(
			Utilities::uri( 'styles/dist/mpress.editor.min.css' ),
		);
		// Apply filters so child themes can add additional styles
		$stylesheets = apply_filters( 'mpress_editor_stylesheets', $stylesheets );
		// Register editor styles
		foreach( $stylesheets as $stylesheet ) {
			add_editor_style( $stylesheet );
		}
	}

	private function normalize_stylesheets( $stylesheets, $filter ) {
		// Allow child themes to add/ammend/replace stylesheets
		$stylesheets = apply_filters( $filter, $stylesheets );
		// Register Styles
		foreach( $stylesheets as $name => $args ) {
			// Define all default arguments
			$defaults = array(
				'src'   => null,
				'deps'  => array(),
				'ver'   => null,
				'media' => 'all',
			);
			// Merge defaults
			$args = wp_parse_args( $args, $defaults );
			// Reset style with merged defaults
			$stylesheets[$name] = $args;
		}
		return $stylesheets;
	}

	private function register_stylesheets( $stylesheets ) {
		foreach( $stylesheets as $name => $args ) {
			wp_register_style( $name, $args['src'], $args['deps'], $args['ver'], $args['media'] );
		}
		// Now we can enqueue each style
		foreach( $stylesheets as $name => $args ) {
			wp_enqueue_style( $name );
		}
	}
}