<?php

/**
 * Custom Background Support
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Background extends \Mpress\Theme {

	/**
	 * Insert Custom Background Styles
	 * Prints custom styles using wp_head()
	 * Callback function declared in Mpress_Theme_Setup class, can be overriden with filter
	 * from that function call
	 * @since 2.0.0
	 * @return (bool) false : Returns false if no custom header styles are set
	 */
	public function insert_styles() {
		$background_image = get_background_image();
		$background_color = get_background_color();
		// If no custom background is set, we can bail
		if ( !$background_image && !$background_color ) {
			return false;
		}
		// Setup some variables
		$style      = $background_color ? "background-color: #$background_color;" : '';
		$body_style = $background_color ? "background-color: #$background_color;" : '';

		if ( $background_image ) {
			// $image_style
			$image  = " background-image: url('$background_image');";
			$repeat = get_theme_mod( 'background_repeat');
			if ( !in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
				$repeat = 'repeat';
			$repeat = " background-repeat: $repeat;";

			$position = get_theme_mod( 'background_position_x', 'left' );
			if ( !in_array( $position, array( 'center', 'right', 'left' ) ) )
				$position = 'left';
			$position = " background-position: top $position;";

			$attachment = get_theme_mod( 'background_attachment', 'scroll' );
			if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
				$attachment = 'scroll';
			$attachment = " background-attachment: $attachment;";

			$style .= $image . $repeat . $position . $attachment;
		}
		include Utilities::path( 'includes/partials/custom-background.php' );
	}
}