<?php

namespace Mdm\RssDisplay;

use \Mdm\RssDisplay\Utilities as Utilities;

class Display extends \Mdm\RssDisplay {

	private static $error = false;

	public function display_feed( $atts = array() ) {
		$default_atts = array(
			'url'   => null,
			'show'  => 5,
			'class' => null,
		);
		$atts = shortcode_atts( $default_atts, $atts, 'display_feed' );
		if( empty( $atts['url'] ) ) {
			return false;
		}
		// Include required PHP file
		include_once( ABSPATH . WPINC . '/feed.php' );
		// Get a SimplePie feed object from the specified feed source.
		$rss = fetch_feed( esc_url_raw( $atts['url'] ) );
		// See if we have an error
		if ( is_wp_error( $rss ) )  {
			_e( 'No items', parent::$plugin_name );
			return false;
		}

		// Figure out how many total items there are, but limit it to specified number
		$atts['show'] = $rss->get_item_quantity( intval( $atts['show'] ) );
		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items( 0, $atts['show'] );
		// Construct output
		$output = sprintf( '<ol class="rssfeed%s">', !empty( $atts['class'] ) ? ' ' . $atts['class'] : '' );
		if( $atts['show'] === 0 ) {
			$output .= sprintf( '<li>%s</li>', __( 'No items', parent::$plugin_name ) );
		} else {
			foreach( $rss_items as $item ) {
				$output .= '<li>';
					$output .= sprintf( '<a href="%s" target="_blank" title="Posted %s" rel="noreferer">', esc_url( $item->get_permalink() ), $item->get_date( 'j F Y | g:i a' ) );
					$output .= esc_html( $item->get_title() );
					$output .= '</a>';
				$output .= '</li>';
			}
		}
		$output .= '</ol>';
		// Do the echo..
		echo $output;
	}

	public function display_feed_shortcode( $atts = array() ) {
		ob_start();
		$this->display_feed( $atts );
		return ob_get_clean();
	}

}