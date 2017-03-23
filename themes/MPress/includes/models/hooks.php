<?php

/**
 * Template hooks that don't have their own mode.
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Hooks extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
	}

	private function register_actions() {
		add_action( 'wp_footer', array( $this, 'makers_mark' ), 9999 );
		add_action( 'copyright_message', array( $this, 'copyright_message' ), 1 );
		add_action( 'mpress_entry_title', array( $this, 'entry_title' ) );
		add_action( 'wp_head', array( $this, 'analytics_file_include' ) );
		add_action( 'wp_after_body', array( $this, 'tag_manager_file_include' ) );

	}

	private function register_filters() {
		add_filter( 'body_class', array( $this, 'body_classes' ) );
	}

	public function makers_mark() {
		// Get image from images directory
		$makers_mark = Utilities::uri( 'images/makers-mark.svg' );
		// Allow child themes to filter to different image
		$makers_mark = apply_filters( 'makers_mark', $makers_mark );
		// Output
		echo sprintf( '<img id="makers-mark" src="%1$s" alt="" style="display: none;">', esc_url_raw( $makers_mark ) );
	}

	public function copyright_message( $atts = array() ) {
		$default_atts = array(
			'name'      => get_bloginfo( 'name' ),
			'message'   => __( 'All Rights Reserved', 'mpress' ),
			'seperator' => ' | ',
			'rel'  => is_front_page() ? 'noopener noreferrer' : 'noopener noreferrer nofollow',
			'designer'  => __( 'Mid-West Family Marketing', 'mpress' ),
		);
		// Parse atts with defaults
		$atts = shortcode_atts( $default_atts, $atts, 'copyright_message' );
		// Construct output
		$output  = sprintf( '<span class="site">Copyright &copy; %1$s %2$s - %3$s</span>', date('Y'), $atts['name'], $atts['message'] );
		$output .= sprintf( '<span class="seperator">%s</span>', $atts['seperator'] );
		$output .= sprintf( ' <span class="designer">Proudly Powered By <a href="http://www.midwestfamilymarketing.com" rel="%1$s" target="_blank">%2$s</a></span>', $atts['rel'], $atts['designer'] );
		echo apply_filters( 'copyright_message_output', $output );
	}


	/**
	 * Adds custom classes to the array of body classes.
	 * @param  (array) $classes : Classes for the body element.
	 * @return (array) $classes : Modified classes array for the body element.
	 */
	public function body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Add menu type class
		$classes[] = get_theme_mod( 'mpress_menu_type' );
		// return array of classesj, filtered by child themes
		$classes;
	}

	private function get_entry_title( $class = 'entry-title' ) {
		if( is_singular() ) {
			return sprintf( '<h1 class="%s">%s</h1>', $class, get_the_title() );
		} else {
			return sprintf( '<h2 class="%s"><a href="%s" rel="bookmark">%s</a></h2>', $class, get_the_permalink(), get_the_title() );
		}
	}

	public function entry_title() {
		if( !empty( $class ) ) {
			echo $this->get_entry_title( $class );
		} else {
			echo $this->get_entry_title();
		}
	}

	public function analytics_file_include() {
		// Path to analytics file
		$path = trailingslashit( get_stylesheet_directory() ) . 'includes/analytics.php';
		// Include file if it exists
		if( file_exists( $path ) ) {
			include $path;
		}
	}

	public function tag_manager_file_include() {
		// Path to analytics file
		$path = trailingslashit( get_stylesheet_directory() ) . 'includes/tag_manager.php';
		// Include file if it exists
		if( file_exists( $path ) ) {
			include $path;
		}
	}

}