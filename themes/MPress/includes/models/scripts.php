<?php

/**
 * Script Functions
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Scripts extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
	}

	  /**
	* Register action hooks w/ WordPress core
	* @since 1.0.0
	* @see https://codex.wordpress.org/Plugin_API/Action_Reference
	*/
	public function register_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_jquery' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'register_login_scripts' ) );
		add_action( 'wp_head', array( $this, 'header_scripts' ) );
		add_action( 'wp_after_body', array( $this, 'body_scripts' ) );
	}
	/**
	* Register Public Scripts
	* @since version 1.0.0
	* @see https://codex.wordpress.org/Function_Reference/wp_enqueue_script
	* @see https://developer.wordpress.org/reference/functions/wp_localize_script
	*/
	public function register_public_scripts() {
		$scripts = array(
			'modernizer' => array(
				'src'      => Utilities::uri( 'scripts/dist/modernizr.custom.min.js' ),
				'deps'     => array(),
				'ver'      => '1.0.0',
				'footer'   => false,
				'localize' => false,
			),
			'mpress-public' => array(
				'src'      => Utilities::uri( 'scripts/dist/mpress.public.min.js' ),
				'deps'     => array( 'jquery' ),
				'ver'      => '1.0.0',
				'footer'   => true,
				'localize' => true,
			),
		);
		// Normalize scripts
		$scripts = $this->normalize_scripts( $scripts, 'mpress_public_scripts' );
		// Register Scripts
		$this->register_scripts( $scripts );
		// Lastly, conditionally enqueue comment reply scripts
		if( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	/**
	* Register Admin Scripts
	* We don't have any admin scripts out of the box, but we provide a loader for child
	* themes to load admin scripts
	* @since 1.0.0
	* @todo documentation
	*/
	public function register_admin_scripts() {
		$scripts = array(
			'mpress-admin' => array(
				'src'      => Utilities::uri( 'scripts/dist/mpress.admin.min.js' ),
				'deps'     => array( 'jquery' ),
				'ver'      => '1.0.0',
				'footer'   => true,
				'localize' => true,
			),
		);
		// Normalize scripts
		$scripts = $this->normalize_scripts( $scripts, 'mpress_admin_scripts' );
		// Register Scripts
		$this->register_scripts( $scripts );
	}
	/**
	* Register Login Scripts
	* We don't have any login scripts out of the box, but we provide a loader for child
	* themes to load login scripts
	* @since 1.0.0
	* @todo documentation
	*/
	public function register_login_scripts() {
		// Define Scripts
		$scripts = array();
		// Normalize scripts
		$scripts = $this->normalize_scripts( $scripts, 'mpress_admin_scripts' );
		// Register Scripts
		$this->register_scripts( $scripts );
	}

	private function normalize_scripts( $scripts, $filter ) {
		// first we allow child themes to extend / manipulate scripts arguments
		$scripts = apply_filters( $filter, $scripts );
		// Then we merge with defaults to ensure script arguments are normalized
		foreach( $scripts as $name => $args ) {
			// Ensure we have all required indexes
			$defaults = array(
				'src'      => null,
				'deps'     => array(),
				'ver'      => null,
				'footer'   => false,
				'localize' => false,
			);
			$args = wp_parse_args( $args, $defaults );
			// Reset Arguments after normalization
			$scripts[$name] = $args;
		}
		return $scripts;
	}

	private function register_scripts( $scripts ) {
		// Register each script
		foreach( $scripts as $name => $args ) {
			// Register Script
			wp_register_script( $name, $args['src'], $args['deps'], $args['ver'], $args['footer'] );
		}
		// Now we can enqueue each script
		foreach( $scripts as $name => $args ) {
			wp_enqueue_script( $name );
		}
		// Finally, we can localize scripts for ajax calls
		foreach( $scripts as $name => $args ) {
			if( $args['localize'] === true ) {
				wp_localize_script( $name, preg_replace( '/[^0-9a-zA-Z_]/', '', $name ), array( 'wpajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			}
		}
	}
	/**
	* Conditionally register jquery from google cdn instead of wordpress core
	* Conditionally load jquery in footer
	* @since 2.0.0
	*/
	public function register_jquery() {
		// If we haven't selected to load from google in customizer, we can bail
		if( get_theme_mod( 'mpress_enqueue_jquery' ) !== 'google' ) {
			return false;
		}
		// Deregister core jquery in favor of cdn version
		wp_deregister_script( 'jquery' );
		// Re-register jquery with CDN URI
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js', array(), '2.1.4', get_theme_mod( 'mpress_jquery_location', false ) );
	}

	/**
	 * Conditionally output theme mod scripts, like analytics
	 * Hooks into the wp_head hook, which should be placed at the bottom of the head file
	 */
	public function header_scripts() {
		$scripts = get_theme_mod( 'mpress_header_scripts', '' );
		if( !empty( $scripts ) ) {
			echo $scripts;
		}
	}

	/**
	 * Conditionally output theme mod scripts, like tag manager
	 * Hooks into wp_after_body, which should be placed directly after the opening body tag
	 */
	public function body_scripts() {
		$scripts = get_theme_mod( 'mpress_body_scripts', '' );
		if( !empty( $scripts ) ) {
			echo $scripts;
		}
	}
}