<?php

namespace Mdm\Recent_Posts\Models;

use \Mdm\Recent_Posts\Models\Utilities as Utilities;

class Display extends \Mdm\Recent_Posts {

	public function get_output( $atts = array() ) {
		$defaults = array(
			'showposts'   => 5,
			'excerpt'     => false,
			'cat'         => null,
			'tag'         => null,
			'exclude_cat' => false,
			'exclude_tag' => false,
			'template'    => 'Default',
			'posttype'    => array( 'post' ),
			'ignore_sticky_posts' => 'on',
		);
		// Parse passed in arguments with defaults
		$atts = wp_parse_args( $atts, $defaults );
		// Normalize bool values in case bools are passed as strings
		$atts = Utilities::normalize_bool_values( $atts );
		// Parse out category / tag arguments
		$atts = $this->get_terms( $atts );
		// Get templates
		$templates = Utilities::get_templates();
		// Construct query
		$wp_query = new \WP_Query( $this->get_query_args( $atts ) );
		// Count for the number of iterations
		$count = 0;
		// Count for the total number of posts
		$post_count = ( isset( $wp_query->found_posts ) && $wp_query->found_posts < $atts['showposts'] ) ? $wp_query->found_posts : $atts['showposts'];
		// Start Output Buffer
		ob_start();
		// Include the markup
		if( isset( $templates[$atts['template']] ) && file_exists( $templates[$atts['template']] ) ) {
			include $templates[$atts['template']];
		}
		// Reset Query
		wp_reset_postdata();
		// Return the contents of the output buffer
		return ob_get_clean();


	}

	public function do_output( $atts = array() ) {
		echo $this->get_output( $atts );
	}

	private function get_terms( $atts ) {
		// First we need to get all terms from strings to an array of ID's if necessary
		if( is_string( $atts['cat'] ) ) {
		    $atts['cat'] = Utilities::parse_terms_from_string( $atts['cat'], 'category' );
		}
		if( is_string( $atts['tag'] ) ) {
		    $atts['tag'] = Utilities::parse_terms_from_string( $atts['tag'], 'post_tag' );
		}
		// Next, We check to see if we are trying to parse category from current page slug
		if( !empty( $atts['cat'] ) && in_array( -1, $atts['cat'] ) ) {
			// Get the position of the special placeholder
			$placeholder_position = array_search( -1, $atts['cat'] );
			// Remove our placeholder
			unset( $atts['cat'][ $placeholder_position ] );
			// Reindex array
			$atts['cat'] = array_values( $atts['cat'] );
			// If we're on a page, try to get a category that matches the current page slug
			if( is_singular( 'page' ) ) {
				$atts['cat'] = $this->current_page_category();
			}
			// Else if we're on a post, get categories that match that post
			elseif( is_singular( 'post' ) ) {
				$atts['cat'] = $this->current_post_category();
			}
		}
		return $atts;
	}

	private function current_page_category() {
		// Get the queried object and sanitize it
		$current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
		// Get array with category id
		$cat_id = Utilities::parse_terms_from_string( $current_page->post_name, 'category' );
		// Return the array of id's
		return $cat_id;
	}

	private function current_post_category() {
		// Setup empty array
		$cat_ids = array();
		// Get all the categories for curren tpost
		$cats = get_the_category();
		// Push ID of each cat into the array
		foreach( $cats as $cat ) {
			$cat_ids[] = $cat->term_id;
		}
		// Return the array of ids
		return $cat_ids;
	}

	private function get_query_args( $atts ) {
		// Initialize query arguments
		$query_args = array( 'post_status' => array( 'publish' ), 'cache_results' => true, 'ignore_sticky_posts' => $atts['ignore_sticky_posts'], 'showposts' => $atts['showposts'] );
		// Set fields
		if( isset( $args['fields'] ) && !empty( $args['fields'] ) ) {
			$query_args['fields'] = $args['fields'];
		}
		// Set categories
		if( isset( $atts['cat'] ) && !empty( $atts['cat'] ) ) {
			switch( $atts['exclude_cat'] ) {
				case 'on' :
					$query_args['category__not_in'] = $atts['cat'];
					break;
				default :
					$query_args['category__in'] = $atts['cat'];
					break;
			}
		}
		// Include Posts
		if( isset( $args['post__in'] ) && !empty( $args['post__in'] ) ) {
			$query_args['post__in'] = $args['post__in'];
		}
		// Exlude Posts
		if(  isset( $args['post__not_in'] ) && !empty( $args['post__not_in'] ) ) {
			$query_args['post__not_in'] = $args['post__not_in'];
		}
		// Set Tags
		if( isset( $atts['tag'] ) && !empty( $atts['tag'] ) ) {
			switch( $atts['exclude_tag'] ) {
				case 'on' :
					$query_args['tag__not_in'] = $atts['tag'];
					break;
				default :
					$query_args['tag__in'] = $atts['tag'];
					break;
			}
		}
		// Set post type
		if( isset( $atts['posttype'] ) && !empty( $atts['posttype'] ) ) {
			$query_args['post_type'] = $atts['posttype'];
		}
		// Send back args
		return $query_args;
	}

}