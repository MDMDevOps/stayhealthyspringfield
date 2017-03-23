<?php

namespace Mdm\Recent_Posts\Widgets;

use \Mdm\Recent_Posts\Models as Models;

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
		$this->widget_id_base = 'mdm_recent_posts_widget';
		$this->widget_name    = 'Better Recent Posts';
		$this->widget_options = array( 'classname' => 'mdm_recent_post', 'description' => 'Better Recent Posts Widget' );
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
			'title'       => null,
			'name'        => null,
			'showposts'   => 5,
			'cat'         => array(),
			'tag'         => array(),
			'exclude_cat' => false,
			'exclude_tag' => false,
			'template'    => 'Default',
			'posttype'    => array(),
			'ignore_sticky_posts' => 'on',
		);
		// merge instance with default values
		$instance = wp_parse_args( (array)$instance, $defaults );
		// Get templates needed for widget form
		$templates = Models\Utilities::get_templates();
		// include our form markup
		include Models\Utilities::path( 'partials/widget_input_form.php' );
	} // end form()

	/**
	 * Update form values
	 * @param $new_instance, $old_instance
	 * @since 1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		// Sanitize / clean values
		$instance = array(
			'title'               => sanitize_text_field( $new_instance['title'] ),
			'name'                => sanitize_text_field( $new_instance['name'] ),
			'showposts'           => intval( $new_instance['showposts'] ),
			'cat'                 => $new_instance['cat'],
			'tag'                 => $new_instance['tag'],
			'exclude_cat'         => $new_instance['exclude_cat'],
			'exclude_tag'         => $new_instance['exclude_tag'],
			'template'            => $new_instance['template'],
			'posttype'            => $new_instance['posttype'],
			'ignore_sticky_posts' => $new_instance['ignore_sticky_posts'],
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
		if( !empty( $instance['name'] ) ) {
			$name = apply_filters( 'widget_title', $instance['name'], $instance, $this->widget_id_base );
			// Again check if filters cleared name, in the case of 'dont show titles' filter or something
			$name = ( !empty( $name ) && $name !== '' && $name !== null ) ? $args['before_title'] . $name . $args['after_title'] : '';
		}
		// Display the markup before the widget (as defined in functions.php)
		echo $before_widget;

		if( !empty( $name ) ) {
			echo $name;
		}

		do_action( 'mdm_recent_posts', $instance );

		echo $after_widget;
	} // end widget()

} // end class