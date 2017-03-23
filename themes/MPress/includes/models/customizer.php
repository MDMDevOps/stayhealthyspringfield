<?php

/**
 * Mpress Customizer
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Customizer extends \Mpress\Theme {

	protected function __construct() {
		$this->register_actions();
		$this->register_filters();
	}

	/**
	 * Register action hooks w/ WordPress core
	 * @since 2.0.0
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
	 */
	public function register_actions() {
		add_action( 'customize_register', array( $this, 'register_scripts' ) );
		add_action( 'customize_register', array( $this, 'register_panels' ) );
		add_action( 'customize_register', array( $this, 'register_sections' ) );
		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'customize_register', array( $this, 'register_controls' ) );
		add_action( 'customize_preview_init', array( $this, 'register_scripts' ) );
	}

	/**
	 * Register action hooks w/ WordPress core
	 * @since 2.0.0
	 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference
	 */
	public function register_filters() {
	}

	/**
	 * Register / Enqueue Script(s)
	 * @since version 2.0.0
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 * @see https://developer.wordpress.org/reference/functions/wp_localize_script
	 */
	public function register_scripts() {
		wp_register_script( 'mpress-customizer', parent::$theme_uri . 'scripts/dist/mpress.customizer.js', array(), null, true );
		wp_enqueue_script(  'mpress-customizer' );
	}

	public function register_panels( $wp_customize ) {

		foreach( $this->get_panels() as $key => $panel ) {
			$wp_customize->add_panel( $key, $panel );
		}
	}

	public function register_sections( $wp_customize ) {
		foreach( $this->get_sections() as $key => $section ) {
			$wp_customize->add_section( $key, $section );
		}
	}

	public function register_settings( $wp_customize ) {
		foreach( $this->get_settings() as $key => $setting ) {
			$wp_customize->add_setting( $key, $setting );
		}
	}

	public function register_controls( $wp_customize ) {
		foreach( $this->get_controls() as $key => $control ) {
			if( isset( $control['wp_customize_class'] ) && !empty( $control['wp_customize_class'] ) ) {
				$wp_customize->add_control( new $control['wp_customize_class']( $wp_customize, $key, $control ) );
			} else {
				$wp_customize->add_control( $key, $control );
			}
		} // endforeach
	}

	private static function get_panels() {
		$panels = array(
			'mpress_theme_options' => array(
				'priority'       => 5,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Theme Options', 'mpress' ),
			),
		);
		return apply_filters( 'mpress_customizer_panels', $panels );
	}

	private static function get_sections() {
		$sections = array(
			'core_theme_settings_section' => array(
				'cabability'  => 'edit_theme_options',
				'title'       => __( 'Core Settings', 'mpress' ),
				'description' => __( 'Customize Core Theme Settings', 'mpress' ),
				'panel'       => 'mpress_theme_options',
				'priority'    => 5,
			),
			'display_theme_settings_section' => array(
				'cabability'  => 'edit_theme_options',
				'title'       => __( 'Display Settings', 'mpress' ),
				'description' => __( 'Customize Core Theme Settings', 'mpress' ),
				'panel'       => 'mpress_theme_options',
				'priority'    => 10,
			),
		);
		return apply_filters( 'mpress_customizer_sections', $sections );
	}

	private static function get_settings() {
		$settings = array(
			'mdm_github_api_key' => array(
				'default'    => '',
				'type'       => 'option',
				'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'mpress_enqueue_jquery' => array(
				'default'    => 'wordpress',
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'mpress_jquery_location' => array(
				'default'    => false,
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'mpress_header_scripts' => array(
				'default'    => null,
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => '',
			),
			'mpress_body_scripts' => array(
				'default'    => null,
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => '',
			),
			'mpress_archive_type' => array(
				'default'    => 'content',
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'mpress_menu_type' => array(
				'default'    => 'dropdown',
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'featured_image_as_header' => array(
				'default'    => false,
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'mpress_default_featured_image' => array(
				'default'    => null,
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'refresh',
				'sanitize_callback' => 'esc_url_raw',
			),
		);
		return apply_filters( 'mpress_customizer_settings', $settings );
	}

	private static function get_controls() {
		$controls = array(
			'mdm_github_api_key' => array(
				'label'       => __( 'Theme API Key', 'mpress' ),
				'description' => __( 'Github access token to enable premium features and updates. Only available if built officially by Mid-West Family Marketing' ),
				'section'     => 'core_theme_settings_section',
				'settings'    => 'mdm_github_api_key',
				'type'        => 'text'
			),
			'mpress_enqueue_jquery' => array(
				'label'     => __( 'Jquery Source', 'mpress' ),
				'description' => __( "Load jQuery from Google's CDN, or WordPress's core version. See ", 'mpress' ) . sprintf( '<a href="https://colorlib.com/wp/load-wordpress-jquery-from-google-library/" target="_blank">%s</a> %s', __( 'this blog post', 'mpress' ), __( 'for a comparison.', 'mpress' ) ),
				'section'   => 'core_theme_settings_section',
				'settings'  => 'mpress_enqueue_jquery',
				'type'      => 'radio',
				'choices'   => array(
					'wordpress' => __( 'Wordpress Core', 'mpress' ),
					'google'    => __( 'Google CDN', 'mpress' )
				)
			),
			'mpress_jquery_location' => array(
				'label'       => __( 'Load JQuery in footer?', 'mpress' ),
				'description' => __( "Load JQuery in the footer for better performance. Only applies if loading jQuery from the Google CDN, and may need to be disabled if other plugins don't declare depencencies correctly", 'mpress' ),
				'section'     => 'core_theme_settings_section',
				'settings'    => 'mpress_jquery_location',
				'type'        => 'checkbox'
			),
			'mpress_header_scripts' => array(
				'label'       => __( 'Header Scripts', 'mpress' ),
				'description' => __( "Scripts to be output in the header, like Google Analytics or other tracking codes. Will output on all pages of the site", 'mpress' ) . '<strong>Note:</strong> Can be filtered using <code>mpress_header_scripts</code> filter.',
				'section'     => 'core_theme_settings_section',
				'settings'    => 'mpress_header_scripts',
				'type'        => 'textarea'
			),
			'mpress_body_scripts' => array(
				'label'       => __( 'Body Scripts', 'mpress' ),
				'description' => __( "Scripts to be output after the opening body tag, like Google Tag Manager Will output on all pages of the site", 'mpress' ) . '<strong>Note:</strong> Can be filtered using <code>mpress_body_scripts</code> filter.',
				'section'     => 'core_theme_settings_section',
				'settings'    => 'mpress_body_scripts',
				'type'        => 'textarea'
			),
			'mpress_archive_type' => array(
				'label'     => __( 'Archive Page Listing Type', 'mpress' ),
				'description' => __( 'How to display content in archive pages, search pages, and other listing pages.', 'mpress'),
				'section'   => 'display_theme_settings_section',
				'settings'  => 'mpress_archive_type',
				'type'      => 'radio',
				'choices'   => array(
					'content' => __( 'Full Content', 'mpress' ),
					'excerpt' => __( 'Excerpt', 'mpress' )
				)
			),
			'mpress_menu_type' => array(
				'label'     => __( 'Mobile Menu Type', 'mpress' ),
				'description' => __( 'How to display the menu on smaller screens', 'mpress'),
				'section'   => 'display_theme_settings_section',
				'settings'  => 'mpress_menu_type',
				'type'      => 'radio',
				'choices'   => array(
					'dropdown'  => __( 'Dropdown Menu', 'mpress' ),
					'offcanvas' => __( 'Off Canvas Menu', 'mpress' )
				)
			),
			'featured_image_as_header' => array(
				'label'       => __( 'Use Featured Image as Custom Header Image', 'mpress' ),
				'description' => __( "If a page or post has a featured image set, use it as the custom header image", 'mpress' ),
				'section'     => 'display_theme_settings_section',
				'settings'    => 'featured_image_as_header',
				'type'        => 'checkbox'
			),
			'mpress_default_featured_image' => array (
				'label'    => __( 'Default Featured Image', 'mpress' ),
				'section'  => 'title_tagline',
				'description' => __( 'Default featured image that can be shown for an individual post or page, if no other featured image is chosen' ),
				'settings' => 'mpress_default_featured_image',
				'type' => 'image',
				'wp_customize_class' => 'WP_Customize_Image_Control',
			)
		);
		return apply_filters( 'mpress_customizer_controls', $controls );
	}
}