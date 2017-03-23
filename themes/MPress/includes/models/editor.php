<?php

/**
 * Editor support
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Editor extends \Mpress\Theme {

	protected function __construct() {
		$this->register_filters();
		$this->register_actions();
	}

	public function register_filters() {
		add_filter( 'mce_buttons_2', array( $this, 'register_style_select' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'register_style_formats' ) );
		add_filter( 'mce_buttons', array( $this, 'register_tinymce_buttons' ) );
		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'the_content', array( $this, 'remove_empty_paragraphs' ), 999999, 1 );
	}

	public function register_actions() {
		add_action( 'wp_ajax_get_theme_presets_modal', array( $this, 'get_theme_presets_modal' ) );
	}

	public function register_style_select( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}

	public function register_style_formats( $init_array ) {
		// Define the style_formats array
		$style_formats = array(
			// Each array child is a format with it's own settings
			'headers' => array(
				'title'  => 'Headers',
				'items'  => array(
					array(
						'title' => 'Header 1',
						'format' => 'h1',
					),
					array(
						'title' => 'Header 2',
						'format' => 'h2',
					),
					array(
						'title' => 'Header 3',
						'format' => 'h3',
					),
					array(
						'title' => 'Header 4',
						'format' => 'h4',
					),
					array(
						'title' => 'Header 5',
						'format' => 'h5',
					),
					array(
						'title' => 'Header 6',
						'format' => 'h6',
					),
				),
			),
			'inline' => array(
				'title'  => 'Inline',
				'items'  => array(
					array(
						'title'  => 'Bold',
						'icon'   => 'bold',
						'format' => 'bold',
					),
					array(
						'title'  => 'Italic',
						'icon'   => 'italic',
						'format' => 'italic',
					),
					array(
						'title'  => 'Underline',
						'icon'   => 'underline',
						'format' => 'underline',
					),
					array(
						'title'  => 'Strikethrough',
						'icon'   => 'strikethrough',
						'format' => 'strikethrough',
					),
					array(
						'title'  => 'Superscript',
						'icon'   => 'superscript',
						'format' => 'superscript',
					),
					array(
						'title'  => 'Subscript',
						'icon'   => 'subscript',
						'format' => 'subscript',
					),
					array(
						'title'  => 'Code',
						'icon'   => 'code',
						'format' => 'code',
					),
					array(
						'title'   => 'Lead',
						'classes' => 'lead',
						'inline' => 'span',
						'wrapper' => false,
					),
				),
			),
			'Blocks' => array(
				'title'  => 'Blocks',
				'items'  => array(
					array(
						'title' => 'Paragraph',
						'format' => 'p',
					),
					array(
						'title' => 'Blockquote',
						'format' => 'blockquote',
					),
					array(
						'title' => 'Div',
						'format' => 'div',
					),
					array(
						'title' => 'Pre',
						'format' => 'pre',
					),
					array(
						'title' => 'Section',
						'format' => 'section',
					),
				),
			),
			'Alignment' => array(
				'title'  => 'Alignment',
				'items'  => array(
					array(
						'title'  => 'Left',
						'icon'   => 'alignleft',
						'format' => 'alignleft',
					),
					array(
						'title'  => 'Right',
						'icon'   => 'alignright',
						'format' => 'alignright',
					),
					array(
						'title'  => 'Center',
						'icon'   => 'aligncenter',
						'format' => 'aligncenter',
					),
					array(
						'title'  => 'Justify',
						'icon'   => 'alignjustify',
						'format' => 'alignjustify',
					),
				),
			),
			'Unordered Lists' => array(
				'title'  => 'Unordered Lists',
				'items'  => array(
					array(
						'title' => '.nobullet',
						'selector' => 'ul',
						'classes' => 'nobullet',
						'wrapper' => false,
					),
					array(
						'title' => '.square',
						'selector' => 'ul',
						'classes' => 'square',
						'wrapper' => false,
					),
					array(
						'title' => '.circle',
						'selector' => 'ul',
						'classes' => 'circle',
						'wrapper' => false,
					),
					array(
						'title' => '.disc',
						'selector' => 'ul',
						'classes' => 'disc',
						'wrapper' => false,
					),
					array(
						'title' => '.bordered',
						'selector' => 'ul',
						'classes' => 'bordered',
						'wrapper' => false,
					),
				),
			),
			'Ordered Lists' => array(
				'title'  => 'Ordered Lists',
				'items'  => array(
					array(
						'title' => '.nobullet',
						'selector' => 'ol',
						'classes' => 'nobullet',
						'wrapper' => false,
					),
					array(
						'title' => '.decimal',
						'selector' => 'ol',
						'classes' => 'decimal',
						'wrapper' => false,
					),
					array(
						'title' => '.decimal-leading-zero',
						'selector' => 'ol',
						'classes' => 'decimal-leading-zero',
						'wrapper' => false,
					),
					array(
						'title' => '.lower-roman',
						'selector' => 'ol',
						'classes' => 'lower-roman',
						'wrapper' => false,
					),
					array(
						'title' => '.upper-roman',
						'selector' => 'ol',
						'classes' => 'upper-roman',
						'wrapper' => false,
					),
					array(
						'title' => '.lower-alpha',
						'selector' => 'ol',
						'classes' => 'lower-alpha',
						'wrapper' => false,
					),
					array(
						'title' => '.upper-alpha',
						'selector' => 'ol',
						'classes' => 'upper-alpha',
						'wrapper' => false,
					),
					array(
						'title' => '.counter',
						'selector' => 'ol',
						'classes' => 'counter',
						'wrapper' => false,
					),
					array(
						'title' => '.bordered',
						'selector' => 'ol',
						'classes' => 'bordered',
						'wrapper' => false,
					),
					array(
						'title' => '.strong',
						'selector' => 'ol',
						'classes' => 'strong',
						'wrapper' => false,
					),
				),
			),
		);
		// Allow filtering further
		$style_formats = apply_filters( 'mpress_style_formats', $style_formats );
		// Json encode and place in array
		$init_array['style_formats'] = json_encode( $style_formats );
		// return to editor
		return $init_array;
	}

	public function register_tinymce_buttons( $buttons ) {
		if( $this->has_theme_presets() ) {
			array_push( $buttons, 'mpresspresets' );
		}
		return $buttons;
	}

	public function add_tinymce_plugin( $plugin_array ) {
		if( $this->has_theme_presets() ) {
			$plugin_array[ 'mpress_presets' ] = parent::$theme_uri . 'scripts/dist/mpress.tinymce.presets.min.js';
		}
		return $plugin_array;
	}

	public function get_theme_presets() {
		$presets = array();
		$theme_presets = apply_filters( 'theme_presets', array() );
		foreach( $theme_presets as $name => $path ) {
			// Start output buffer
			ob_start();
			// include path
			include $path;
			// Construct response
			$presets[$name] = array(
				'path'    => $path,
				'content' => ob_get_clean(),
			);
		}
		return $presets;
	}

	public function has_theme_presets() {
		$presets = apply_filters( 'theme_presets', array() );
		return !empty( $presets );
	}

	public function get_theme_presets_modal() {
		$presets = $this->get_theme_presets();
		$first = current( $presets );
		ob_start();
		include parent::$theme_path . 'includes/partials/theme_presets_modal.php';
		$presets_modal = array(
			'presets' => $presets,
			'modal'   => ob_get_clean(),
		);
		echo json_encode( $presets_modal );
		exit();
	}

	public function remove_empty_paragraphs( $content ) {
		// $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		// $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
		// $content = preg_replace( '/<p[^>]*><\\/p[^>]*>/', '', $content );
		// $content = force_balance_tags( $content );
		// $content = str_replace( '<p></p>', '', $content );
		// $content = force_balance_tags( $content );
		 $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		 $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
		return $content;
	}
}