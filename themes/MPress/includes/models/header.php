<?php

/**
 * Custom Header Support
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Header extends \Mpress\Theme {

	protected function __construct() {
		add_filter( 'theme_mod_header_image', array( $this, 'filter_header_image' ), 25 );
		add_filter( 'theme_mod_header_image_data', array( $this, 'filter_header_image_data' ), 25 );
	}

	/**
	 * Insert Custom Header Styles
	 * Prints custom styles using wp_head()
	 * Callback function declared in 'register_custom_header' function, can be overriden with filter
	 * from that function call
	 * @since 2.0.0
	 * @return (bool) false : Returns false if no custom header styles are set
	 */
	public function insert_styles() {
		$header_text_color   = get_header_textcolor();
		$header_text_display = display_header_text();
		// If both are default, we can bail...
		if( $header_text_color === false && $header_text_display === true ) {
			return false;
		}
		include Utilities::path( 'includes/partials/custom_header.css.php' );
	}

	public function filter_header_image( $image_url ) {
		// Setting must be true
		if( $this->use_featured_image_as_header() === true ) {
			$image_url = get_the_post_thumbnail_url();
		}
		// Return the image URL
		return apply_filters( 'replace_featured_image_as_header',  $image_url );
	}
	/* Filter the header image. */


	public function filter_header_image_data( $image_data ) {
		if( $this->use_featured_image_as_header() === true ) {
			// Get image information from db
			$featured_id = get_post_thumbnail_id();
			$featured_data = wp_get_attachment_metadata( $featured_id );
			// Construct new image data
			$image_data->url = get_the_post_thumbnail_url();
			$image_data->attachment_id = $featured_id;
			$image_data->width = $featured_data['width'];
			$image_data->height = $featured_data['height'];
			$image_data->thumbnail_url = get_the_post_thumbnail_url( get_the_id(), 'thumbnail' );
		}
		return $image_data;
	}

	private function use_featured_image_as_header() {
		$use_featured_img   = get_theme_mod( 'featured_image_as_header' );
		$use_on_type        = apply_filters( 'custom_header_post_type', get_post_types() );
		$this_type          = get_post_type();
		$has_post_thumbnail = has_post_thumbnail();
		// If setting is not set to use this feature
		if( $use_featured_img !== '1' ) {
			return false;
		}
		// If post is not of correct type (any type is default, but can be filtered by child themes)
		if( in_array( $this_type, $use_on_type ) === false ) {
			return false;
		}
		// If this doesn't have a featured image
		if( $has_post_thumbnail === false ) {
			return false;
		}
		return true;
	}
}