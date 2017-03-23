<?php

/**
 * Entry Meta Functionality
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Meta extends \Mpress\Theme {

	protected function __construct() {
		add_action( 'mpress_entry_meta', array( $this, 'output' ) );
	}

	public function get( $atts = array() ) {
		// Setup defaults
		$default_atts = array(
			'meta_type'   => array( 'author', 'date', 'categories', 'post_tags', 'comments', 'edit' ),
			'post_type'   => get_post_types(),
			'date_format' => null,
		);
		// Allow child themes to filter defaults for easier usage
		$default_atts = apply_filters( 'default_entry_meta', $default_atts );
		// Merge with passed in atts
		$atts = shortcode_atts( $default_atts, $atts, 'entry_meta' );
		// Allow meta to be filtered by post type
		if( in_array( get_post_type(), $atts['post_type'] ) === false ) {
			return false;
		}
		// Start constructing our output
		$output = '<ul class="mpress_entry_meta">';
			// Append each type of meta data
			foreach( $atts['meta_type'] as $meta_name ) {
				$function = sprintf( 'get_%s_meta', $meta_name );
				$output .= $this->$function( $atts );
			}
		$output .= '</ul>';

		return $output;
	}

	public function output( $atts = array() ) {
		echo $this->get( $atts );
	}

	private function get_date_meta( $atts ) {
		// Structure post date
		$post_date  = sprintf( '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time>', esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date( $atts['date_format'] ) ) );
		// If necessary, append the updated datetime
		$post_date .= ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) ? sprintf( '<time class="updated screen-reader-text" datetime="%1$s" itemprop="dateModified">%2$s</time>', esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date( $atts['date_format'] ) ) ) : '';
		// Structure output
		$output = sprintf( '<li class="date">%s<a href="%s" rel="bookmark">%s</a></li>', apply_filters( 'get_mpress_icon', array( 'name' => 'time' ) ), get_permalink(), $post_date );
		return $output;
	}

	private function get_categories_meta( $atts ) {
		$cats = get_the_category_list();
		if( empty( $cats ) ) {
			return;
		}
		$output = '<li class="categories">';
			$output .= apply_filters( 'get_mpress_icon', array( 'name' => 'category' ) );
			$output .= sprintf( '<span itemprop="keywords">%s</span>', get_the_category_list( '</span>, <span itemprop="keywords">' ) );
		$output .= '</li>';
		return $output;
	}

	private function get_post_tags_meta( $atts ) {
		if( !get_the_tags() ) {
			return;
		}
		$output = '<li class="post_tags">';
			$output .= apply_filters( 'get_mpress_icon', array( 'name' => 'tag' ) );
			$output .= sprintf( '<span itemprop="keywords">%s</span>', get_the_tag_list( '</span>, <span itemprop="keywords">' ) );
		$output .= '</li>';
		return $output;
	}

	private function get_comments_meta( $atts ) {
		// If comments are closed, or doesn't have any comments, bail...
		if( !comments_open() ) {
			return;
		}
		$output = '<li class="comments">';

			$output .= apply_filters( 'get_mpress_icon', array( 'name' => 'comment' ) );
			$output .= sprintf( '<a href="%s">', esc_url_raw( get_comments_link() ) );
				$comments = get_comments_number();
				switch( $comments ) {
				    case 0 :
				        $output .= sprintf( '<span class="screen-reader-text" itemprop="commentCount">%d</span>%s', $comments, __( 'No Comments', 'mpress' ) );
				        break;
				    case 1 :
				        $output .= __( 'One Comment', 'mpress' );
				        break;
				    default :
				        $output .= sprintf( '<span itemprop="commentCount">%d</span> %s', $comments, __( 'Comments', 'mpress' ) );
				        break;
				} // end switch
			$output .= '</a>';
		$output .= '</li>';
		return $output;
	}

	private function get_author_meta( $atts ) {
		$output  = '<li class="author vcard">';
			$output .= apply_filters( 'get_mpress_icon', array( 'name' => 'author' ) );
			$output .= '<span itemprop="author" itemscope itemtype="https://schema.org/Person">';
				$output .= sprintf( '<a itemprop="url" href="%1$s"><span itemprop="name">%2$s</span></a>', esc_url_raw( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) );
			$output .= '</span>';
		$output .= '</li>';
		return $output;
	}

	private function get_edit_meta( $atts ) {
		if( !current_user_can( 'edit_post', get_current_user_id() ) ) {
			return;
		}
		$output = '<li class="edit">';
			$output .= apply_filters( 'get_mpress_icon', array( 'name' => 'edit' ) );
			$output .= sprintf( '<a href="%1$s">%2$s</a>', get_edit_post_link(), __( 'Edit', 'mpress' ) );
		$output .= '</li>';
		return $output;
	}

}