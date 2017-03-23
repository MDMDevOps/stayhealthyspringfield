<?php

/**
 * Mpress Comments
 * @since 2.0.0
 * @todo document module, add link here to wiki page
 * @package mpress
 */

namespace Mpress\Models;

use \Mpress\Models\Utilities as Utilities;

class Comments extends \Mpress\Theme {

	protected function __construct() {
		$this->register_filters();
	}

	/**
	 * Register filters w/ WordPress core
	 * @since 2.0.0
	 */
	public function register_filters() {
		add_filter( 'comment_form_fields', array( $this, 'comment_form_fields' ), 1, 10 );
		add_filter( 'comment_form_fields', array( $this, 'reorder_comment_fields' ) );
		add_action( 'widgets_init', array( $this, 'remove_recent_comments_style' ) );
	}

	public static function list_comments( $comment, $args, $depth  ) {
		//checks if were using a div or ol|ul for our output
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		// include template, overridable by child themes
		include locate_template( 'views/comments/comment.php' );
	}

	public function comment_form_fields( $form_fields ) {
		// Define some stock wordpress variables
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		// Get theme fields
		$fields = array(
			'author'  => $this->get_author_field( $req, $commenter ),
			'email'   => $this->get_email_field( $req, $commenter ),
			'url'     => $this->get_website_field( $req, $commenter ),
			'comment' => $this->get_comment_field( $req, $commenter ),
		);
		$fields = wp_parse_args( $fields, $form_fields );
		// Return
		return $fields;
	}

	private function get_author_field( $req, $commenter ) {
		ob_start();
		include locate_template( 'views/comments/form_author_field.php' );
		return apply_filters( 'mpress_comment_author_form_field', ob_get_clean() );
	}
	private function get_email_field( $req, $commenter ) {
		ob_start();
		include locate_template( 'views/comments/form_email_field.php' );
		return apply_filters( 'mpress_comment_email_form_field', ob_get_clean() );
	}
	private function get_website_field( $req, $commenter ) {
		ob_start();
		include locate_template( 'views/comments/form_website_field.php' );
		return apply_filters( 'mpress_comment_website_form_field', ob_get_clean() );
	}
	private function get_comment_field( $req, $commenter ) {
		ob_start();
		include locate_template( 'views/comments/form_comment_field.php' );
		return apply_filters( 'mpress_comment_comment_form_field', ob_get_clean() );
	}

	public function reorder_comment_fields( $fields ) {
		$comment_field = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $comment_field;
		return $fields;
	}

	/**
	 * Remove Authomatically injected style for recent comments widget
	 * @since version 2.0.0
	 */
	function remove_recent_comments_style() {
	    global $wp_widget_factory;
	    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
	}

}