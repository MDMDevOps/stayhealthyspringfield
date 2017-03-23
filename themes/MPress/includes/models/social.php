<?php

/**
 * Social
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Social extends \Mpress\Theme {

		protected function __construct() {
			$this->register_actions();
			$this->register_filters();
			$this->register_shortcodes();
		}

		/**
		 * Register action hooks w/ WordPress core
		 * @since 2.0.0
		 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
		 */
		public function register_actions() {
			add_action( 'social_networks', array( $this, 'social_networks_action' ) );
		}

		/**
		 * Register action hooks w/ WordPress core
		 * @since 2.0.0
		 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference
		 */
		public function register_filters() {
			add_filter( 'mpress_customizer_sections', array( $this, 'register_customizer_sections' ) );
			add_filter( 'mpress_customizer_settings', array( $this, 'register_customizer_settings' ) );
			add_filter( 'mpress_customizer_controls', array( $this, 'register_customizer_controls' ) );
		}

		public function register_shortcodes() {
			add_shortcode( 'social_networks', array( $this, 'social_networks_shortcode' ) );
		}

		public function get_network_list() {
			$networks = array(
				'facebook' => array(
					'slug'    => 'facebook_uri',
					'default' => null,
					'label'   => __( 'Facebook URI', 'mpress' ),
				),
				'twitter' => array(
					'slug'    => 'twitter_uri',
					'default' => null,
					'label'   => __( 'Twitter URI', 'mpress' ),
				),
				'googleplus' => array(
					'slug'    => 'googleplus_uri',
					'default' => null,
					'label'   => __( 'Google Plus URI', 'mpress' ),
				),
				'youtube' => array(
					'slug'    => 'youtube_uri',
					'default' => null,
					'label'   => __( 'Youtube URI', 'mpress' ),
				),
				'linkedin' => array(
					'slug'    => 'linkedin_uri',
					'default' => null,
					'label'   => __( 'Linkedin URI', 'mpress' ),
				),
				'pinterest' => array(
					'slug'    => 'pinterest_uri',
					'default' => null,
					'label'   => __( 'Pinterest URI', 'mpress' ),
				),
				'instagram' => array(
					'slug'    => 'instagram_uri',
					'default' => null,
					'label'   => __( 'Instagram URI', 'mpress' ),
				)
			);
			return apply_filters( 'mpress_social_networks', $networks );
		}

		public function register_customizer_sections( $sections ) {
			$sections['social_settings_section'] = array(
				'cabability'  => 'edit_theme_options',
				'title'       => __( 'Social Networks', 'mpress' ),
				'description' => __( 'Set Social Network URI\'s', 'mpress' ) . ', use <code>[social_networks]</code> to output.',
				'panel'       => 'mpress_theme_options',
				'priority'    => 10
			);
			return $sections;
		}

		public function register_customizer_settings( $settings ) {
			foreach( $this->get_network_list() as $key => $network ) {
				$settings[ $network['slug'] ] = array(
					'default'           => $network['default'],
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'transport'         => 'refresh',
					'sanitize_callback' => 'esc_url_raw'
				);
			}
			return $settings;
		}

		public function register_customizer_controls( $controls ) {
			foreach( $this->get_network_list() as $key => $network ) {
				$controls[ $network['slug'] ] = array(
					'label'    => $network['label'],
					'section'  => 'social_settings_section',
					'setting'  => $network['slug'],
					'type'     => 'text',
				);
			}
			return $controls;
		}

		public function get_networks_output( $args = array() ) {
			// Begin output
			$output = '<ul class="social">';
			foreach( $this->get_network_list() as $name => $setting ) {
				// Get setting from database
				$network_uri = get_option( $setting['slug'], null );
				// Append LI if network is set
				if( !empty( $network_uri ) ) {
					$output .= sprintf( '<li class="%1$s">', $name );
						$output .= sprintf( '<a class="social-link" href="%1$s" target="_blank" rel="noopener noreferrer">', esc_url_raw( $network_uri ) );
							// Conditionally output icon
							$output .= ( $args['icon'] === true ) ? apply_filters( 'get_mpress_icon', $name ) : '';
							// Conditionally hide / show text
							$output .= ( $args['text'] === true ) ? '<span class="social-name">' : '<span class="social-name screen-reader-text">';
							// Close span
							$output .= sprintf( '%1$s</span>', $name );
						// Close link
						$output .= '</a>';
					// Close list item
					$output .= '</li>';
				}
			}
			$output .= '</ul>';
			return $output;
		}

		public function social_networks_shortcode( $atts = array() ) {
			$defaults = array(
				'network' => null,
				'icon'    => true,
				'text'    => false,
			);
			// Merge shortcode attributes
			$atts = shortcode_atts( $defaults, $atts, 'social_network_list' );
			// Normalize attributes
			$atts = $this->normalize_atts( $atts );
			// Return the value
			return $this->get_networks_output( $atts );
		}

		public function social_networks_action( $atts = array() ) {
			echo $this->social_networks_shortcode( $atts );
		}

		/**
		 * Normalize boolean attributes
		 * Transforms text 'true' / 'false' to bool true / false
		 */
		private function normalize_atts( $atts ) {
			foreach( $atts as $name => $att ) {
				if( $att === 'true' ) {
					$atts[$name] = true;
				}
				elseif( $att === 'false' ) {
					$atts[$name] = false;
				}
			}
			return $atts;
		}
}