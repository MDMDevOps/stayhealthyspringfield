<?php

/**
 * Theme Setup and supports
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Setup extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
	}

	private function register_actions() {
		add_action( 'after_setup_theme', array( $this, 'register_theme_supports' ) );
		add_action( 'init', array( $this, 'clean_head' ) );
	}

	private function register_filters() {
		add_filter( 'style_loader_src', array( $this, 'remove_script_version' ), 9999 );
		add_filter( 'script_loader_src', array( $this, 'remove_script_version' ), 9999 );
		add_filter( 'the_generator', array( $this, 'remove_rss_version' ) );
	}

	public function register_theme_supports() {
		$theme_supports = apply_filters( 'mpress_theme_supports', array(
			'automatic-feed-links' => true,
			'title-tag' => true,
			'post-thumbnails' => true,
			'post-formats' => array(
				'aside',
				'audio',
				'video',
				'chat',
				'gallery',
				'image',
				'quote',
				'status',
				'link'
			),
			'html5' => array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			),
			'custom-logo' => array(
				'height' => 200,
				'width'  => 300,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array(
					'site-title',
					'site-description',
				)
			),
			'custom-header' => array(
				'default-image' => null,
				'default-text-color' => null,
				'width' => 2560,
				'height' => 1440,
				'flex-height' => true,
				'flex-width' => true,
				'wp-head-callback' => array( \Mpress\Theme::get_instance( '\\Mpress\\Models\\Header' ), 'insert_styles' ),
				'admin-head-callback' => null,
				'admin-preview-callback' => null,
			),
			'custom-background' => array(
				'default-color'          => '',
				'default-image'          => '',
				'default-repeat'         => '',
				'default-position-x'     => '',
				'default-attachment'     => '',
				'wp-head-callback'       => array( \Mpress\Theme::get_instance( '\\Mpress\\Models\\Background' ), 'insert_styles' ),
				'admin-head-callback'    => '',
				'admin-preview-callback' => '',
			),
		) );

		foreach( $theme_supports as $feature => $args ) {
			if( is_array( $args ) ) {
				add_theme_support( $feature, $args );
			} else {
				add_theme_support( $feature );
			}
		}
	}

	public function clean_head() {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	/**
	 * I18n Language Support
	 * @since version 1.0.0
	 * @see   https://codex.wordpress.org/I18n_for_WordPress_Developers
	 * @see   https://codex.wordpress.org/Function_Reference/load_theme_textdomain
	 */
	public static function register_text_domain() {
		load_theme_textdomain( parent::$theme_name, parent::$theme_path . 'i18n' );
	}

	/**
	 * Remove Version Number From Enqueued Scripts & Styles
	 * @since version 1.0.0
	 */
	public function remove_script_version( $src ) {
	    if ( strpos( $src, 'ver=' ) )
	        $src = remove_query_arg( 'ver', $src );
	    return $src;
	}

	function remove_rss_version() {
	    return '';
	}
}