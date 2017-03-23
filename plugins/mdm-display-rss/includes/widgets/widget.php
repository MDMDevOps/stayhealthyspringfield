<?php

namespace Mdm\RssDisplay\Widgets;

use \Mdm\RssDisplay\Utilities as Utilities;

class Widget extends \WP_Widget {

	public $widget_id_base;
	public $widget_name;
	public $widget_options;
	public $control_options;
	public $templates = array();

	/**
	 * Constructor, initialize the widget
	 * @param $id_base, $name, $widget_options, $control_options ( ALL optional )
	 * @since 1.0.0
	 */
	public function __construct() {
		// Construct some options
		$this->widget_id_base = 'mdm_rss_display';
		$this->widget_name    = 'RSS Feed';
		$this->widget_options = array( 'classname' => 'mdm_rss_display', 'description' => 'Display RSS Feed' );
		// Construct parent
		parent::__construct( $this->widget_id_base, $this->widget_name, $this->widget_options );
	}

	/**
	 * Create back end form for specifying image and content
	 * @param $instance
	 * @see https://codex.wordpress.org/Function_Reference/wp_parse_args
	 * @since 1.0.0
	 */
	public function form( $instance ) {
		// define our default values
		$defaults = array(
			'title'     => null,
			'showposts' => 10,
			'feeduri'   => null,
			'hidetitle' => false,
		);
		// merge instance with default values
		$instance = wp_parse_args( (array)$instance, $defaults );
		// Get ad group terms
		$terms = get_terms( array( 'taxonomy' => 'adgroup', 'hide_empty' => false ) );
		// Do some error checking
		$terms = is_array( $terms ) ? $terms : array();
		// include our form markup
		include Utilities::path( 'partials/forms/widget.php' );
	} // end form()

	/**
	 * Update form values
	 * @param $new_instance, $old_instance
	 * @since 1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		// Sanitize / clean values
		$instance = array(
			'title'     => sanitize_text_field( $new_instance['title'] ),
			'showposts' => intval( $new_instance['showposts'] ),
			'adgroup'   => intval( $new_instance['adgroup'] ),
			'hidetitle' => sanitize_text_field( $new_instance['hidetitle'] ),
		);
		// Merge values
		$instance = wp_parse_args( $instance, $old_instance );
		// Return values
		return $instance;
	} // end update()

	/**
	 * Output widget on the front end
	 * @param $args, $instance
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		// Extract the widget arguments ( before_widget, after_widget, description, etc )
		extract( $args );
		// Instantiate $heading to avoid errors
		$name = '';
		// Append before / after title elements if title is not blank
		if( !empty( $instance['title'] ) ) {
			$instance['title']  = apply_filters( 'widget_title', $instance['title'], $instance, $this->widget_id_base );
			// Again check if filters cleared name, in the case of 'dont show titles' filter or something
			$instance['title']  = ( !empty( $instance['title']  ) ) ? $args['before_title'] . $instance['title']  . $args['after_title'] : '';
		}
		// Display the markup before the widget (as defined in functions.php)
		echo $before_widget;
		// Display the title
		if( $instance['hidetitle'] !== 'on' ) {
			echo $instance['title'] ;
		}
		// DO WIDGET ACTION
		do_action( 'display_adgroup', array( 'adgroup' => $instance['adgroup'], 'showposts' => $instance['showposts'] ) );
		// Echo after widget args
		echo $after_widget;
	} // end widget()

} // end class