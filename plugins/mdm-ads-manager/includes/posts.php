<?php

namespace Mdm\AdsManager;

use \Mdm\AdsManager\Utilities as Utilities;

class Posts extends \Mdm\AdsManager {

	protected static $post_type;

	public function get_meta_boxes() {
		return array();
	}

	public function get_type() {
		return '';
	}

	public function normalize_metabox_fields( $name = '', $post_values = array() ) {
		$metaboxes = $this->get_meta_boxes();
		// If name not set, return empty array
		if( empty( $name ) || !isset( $metaboxes[$name] ) ) {
			return array();
		}
		// Set empty array to hold parsed values
		$values = array();
		// Check each field
		foreach( $metaboxes[$name]['fields'] as $field_name => $field_args ) {
			// If the field name is set in fields passed in from $_POST
			if( isset( $post_values[$field_name] ) ) {
				// Check sanitize function is properly set
				if( !empty( $field_args['sanitize'] ) && is_callable( $field_args['sanitize'] ) ) {
					$values[$field_name] = call_user_func( $field_args['sanitize'], $post_values[$field_name] );
				} else {
					$values[$field_name] = $post_values[$field_name];
				}
			} else {
				$values[$field_name] = $field_args['value'];
			}

		}
		// Return the fieldset
		return $values;
	}

	public function add_meta_boxes( $post_type ) {
		$admin = \Mdm\AdsManager\Admin::get_instance();
		// Get array of metaboxes
		$metaboxes = $this->get_meta_boxes();
		// Add each metabox, with data
		foreach( $metaboxes as $metabox_name => $metabox_data ) {
			// $post_meta   = get_post_meta( get_the_id(), $metabox_name, true );
			$box_options = array(
				'meta' => $this->normalize_metabox_fields( $metabox_name, get_post_meta( get_the_id(), $metabox_name, true ) ),
			);
			$box_options = apply_filters( sprintf( '%s_metabox_data', $metabox_name ), $box_options );
			add_meta_box( $metabox_name, $metabox_data['title'], array( $this, 'display_metaboxes' ), $metabox_data['post_type'], $metabox_data['placement'], $metabox_data['priority'], $box_options );
		}
	}

	public function display_metaboxes( $post, $metabox ) {
		// Generate standard nonce names
		$nonce = Utilities::get_nonce( $metabox['id'] );
		// Add nonce field
		wp_nonce_field( $nonce['key'], $nonce['nonce'] );
		// Display the metabox
		printf( '<div class="metabox %s %s">', parent::$plugin_name, $metabox['id'] );
			include Utilities::path( sprintf( 'partials/metaboxes/%s.php', $metabox['id'] ) );
		echo '</div>';
		// Push metabox ID onto the array of known metaboxes
	}

	public function save_metaboxes( $post_id, $post ) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// Get list of all metaboxes
		$metaboxes = $this->get_meta_boxes();
		// Loop through returned metaboxes and trigger appropriate actions
		foreach( $metaboxes as $metabox_name => $metabox_args ) {
			// Generate standard nonce names
			$nonce = Utilities::get_nonce( $metabox_name );
			// Verify passed nonce vs stored nonce
			if( isset( $_POST[ $nonce['nonce'] ] ) && wp_verify_nonce( $_POST[ $nonce['nonce'] ], $nonce['key'] ) ) {
				// Normalize / sanitize the values
				$metabox_values = $this->normalize_metabox_fields( $metabox_name, $_POST );
				//Update the post meta
				update_post_meta( $post_id, $metabox_name, $metabox_values );
				//Hook into after save
				if( $metabox_name === 'displayrules' ) {
					update_option('rule_debug', '');
				}
				do_action( 'after_metabox_save', array( 'post_id' => $post_id, 'values' => $metabox_values ) );

			}
		}
	}

	public function get_all( $query_args = array() ) {
		// // Initialize empty array to hold results
		$posts = array();
		// WP_Query arguments
		$default_query_args = array (
			'post_status'    => array( 'publish' ),
			'posts_per_page' => '-1',
			'cache_results'  => true,
			'post_type'      => array( $this->get_type() ),
		);
		// Merge args
		$query_args = wp_parse_args( $query_args, $default_query_args );
		// The Query
		$wp_query = new \WP_Query( $query_args );
		// The Loop
		if( $wp_query->have_posts() ) :
			while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$posts[] = $wp_query->post;
			endwhile;
		endif;
		// Restore original Post Data
		wp_reset_postdata();
		// Return array of post objects
		return $posts;
	}
}