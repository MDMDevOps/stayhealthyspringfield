<?php

/**
 * Custom Header Support
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;
use \DOMDocument as DOMDocument;

class Media extends \Mpress\Theme {

	protected function __construct() {
		$this->register_filters();
	}

	private function register_filters() {
		add_filter( 'image_send_to_editor', array( $this, 'insert_image_classes' ), 10, 8 );
		add_filter( 'attachment_fields_to_edit', array( $this, 'add_media_fields' ), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'save_media_fields' ), 10, 2 );
		add_filter( 'post_mime_types', array( $this, 'modify_mime_types' ) );
		add_filter( 'upload_mimes', array( $this, 'allow_svg_upload' ) );
		add_filter( 'embed_oembed_html', array( $this, 'responsive_oembed' ), 99, 4 );
	}

	public function insert_image_classes( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {
		$presets = get_post_meta( $id, 'preset_classes', true );
		$classes = get_post_meta( $id, 'additional_classes', true );
		// If these weren't retrieved, we can bail
		if( $presets === false && $classes === false ) {
			return $html;
		}
		// If these are empty, we can send it back to the editor untouched
		if( empty( $presets ) && empty( $classes ) ) {
			return $html;
		}
		// Load the document
		$dom = new DOMDocument;
		$dom->loadHTML( $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		// Add classes to img tag
		foreach( $dom->getElementsByTagName( 'img' ) as $img ) {
		   $img->setAttribute( 'class', trim( $img->getAttribute('class') . ' ' . esc_attr( $presets ) ) );
		   $img->setAttribute( 'class', trim( $img->getAttribute('class') . ' ' . esc_attr( $classes ) ) );
		}
		// Add classes and alt to a tag
		foreach( $dom->getElementsByTagName( 'a' ) as $link ) {
		   $link->setAttribute( 'class', trim( $link->getAttribute('class') . ' ' . esc_attr( $presets ) . ' ' . esc_attr( $classes ) ) );
		}
		return $dom->saveHTML();
	}

	public function add_media_fields( $fields, $post ) {
		// Allow child themes to add additionals
		$presets = apply_filters( 'mpress_image_presets', array() );
		// Only add if there are actually presets to use
		if( is_array( $presets ) && !empty( $presets ) ) {
			$fields['preset_classes'] = array(
				'label' => 'Presets',
				'input' => 'html',
			);
			$fields['preset_classes']['html']  = "<select name='attachments[$post->ID][preset_classes]' class='widefat'>";
			$fields['preset_classes']['html'] .= sprintf( '<option value="" %s>None</option>', selected( get_post_meta( $post->ID, "preset_classes", true ), '', false ) );
			foreach( $presets as $value => $name ) {
				$fields['preset_classes']['html'] .= sprintf( '<option value="%s" %s>%s</option>', $value, selected( get_post_meta( $post->ID, "preset_classes", true ), $value, false ), $name );
			}
			$fields['preset_classes']['html'] .= '</select>';
		}
		// Responsive Alignment

		// Additional Classes
		$fields['additional_classes'] = array(
			'label' => 'CSS Classes',
			'input' => 'text',
			'classes' => 'widefat',
			'value' => get_post_meta( $post->ID, 'additional_classes', true ),
		);
		return $fields;
	}

	public function save_media_fields( $post, $attachment ){
		// Update presets
		if( isset( $attachment['preset_classes'] ) && !empty( $attachment['preset_classes'] ) ) {
			update_post_meta( $post['ID'], 'preset_classes', $attachment['preset_classes'] );
		} else {
			update_post_meta( $post['ID'], 'preset_classes', '' );
		}
		// Update additional classes
		if( isset( $attachment['additional_classes'] ) && !empty( $attachment['additional_classes'] ) ) {
			update_post_meta( $post['ID'], 'additional_classes', $attachment['additional_classes'] );
		} else {
			update_post_meta( $post['ID'], 'additional_classes', '' );
		}
	}

	public function modify_mime_types( $post_mime_types ) {
		$post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
		return $post_mime_types;
	}

	/**
	 * Allow SVG's to be uploaded via the media uploader
	 * @since version 1.0.0
	 */
	public function allow_svg_upload( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function responsive_oembed( $html, $url, $attr, $post_id ) {
		// Create element to hold our fragments
		$embed = array(
			'width'  => null,
			'height' => null,
			'domain' => null,
		);
		// Create DOM element, and extract code
		$dom = new DOMDocument();
		$dom->loadHTML( $html );
		$el = $dom->getElementsByTagName('iframe');
		// Extract width and height
		if( $el->length ) {
			foreach( $el as $iframe ) {
				$embed['width']  = $iframe->getAttribute( 'width' );
				$embed['height'] = $iframe->getAttribute( 'height' );
			}
		}
		// Get domain
		$embed['domain'] = parse_url( $url );
		// Remove WWW extension
		$embed['domain'] =  str_replace( 'www.', '', $embed['domain']['host'] );
		// Replace remaining periods with dashed
		$embed['domain'] =  preg_replace('/[.]/', '-', $embed['domain'] );
		// Construct Attributes
		$style = ( $embed['height'] && $embed['width'] ) ? sprintf( 'style="padding-bottom: %.3f%%;"', ( $embed['height'] / $embed['width'] ) * 100 ) : null;
		$class = ( $embed['domain'] ) ? sprintf( 'class="oembed flex-video %s"', $embed['domain'] ) : 'class="oembed flex-video"';
		// return output
		return sprintf( '<div %s %s data-url="%s">%s</div>', $class, $style, $url, $html );
	}
}