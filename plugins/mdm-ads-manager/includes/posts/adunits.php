<?php

namespace Mdm\AdsManager\Posts;

use \Mdm\AdsManager\Utilities as Utilities;

class AdUnits extends \Mdm\AdsManager\Posts {

	protected static $post_type = 'adunit';

	public function get_type() {
		return self::$post_type;
	}

	public static function register() {
		$labels = array(
			'name'                  => _x( 'Ad Units', 'Post Type General Name', parent::$plugin_name ),
			'singular_name'         => _x( 'Ad Unit', 'Post Type Singular Name', parent::$plugin_name ),
			'menu_name'             => __( 'Ad Units', parent::$plugin_name ),
			'name_admin_bar'        => __( 'Ad Unit', parent::$plugin_name ),
			'archives'              => __( 'Ad Unit Archives', parent::$plugin_name ),
			'attributes'            => __( 'Ad Unit Attributes', parent::$plugin_name ),
			'parent_item_colon'     => __( 'Parent Ad Unit:', parent::$plugin_name ),
			'all_items'             => __( 'All Ad Units', parent::$plugin_name ),
			'add_new_item'          => __( 'Add New Ad Unit', parent::$plugin_name ),
			'add_new'               => __( 'Add New', parent::$plugin_name ),
			'new_item'              => __( 'New Ad Unit', parent::$plugin_name ),
			'edit_item'             => __( 'Edit Ad Unit', parent::$plugin_name ),
			'update_item'           => __( 'Update Ad Unit', parent::$plugin_name ),
			'view_item'             => __( 'View Ad Unit', parent::$plugin_name ),
			'view_items'            => __( 'View Ad Units', parent::$plugin_name ),
			'search_items'          => __( 'Search Ad Units', parent::$plugin_name ),
			'not_found'             => __( 'Not found', parent::$plugin_name ),
			'not_found_in_trash'    => __( 'Not found in Trash', parent::$plugin_name ),
			'featured_image'        => __( 'Featured Image', parent::$plugin_name ),
			'set_featured_image'    => __( 'Set featured image', parent::$plugin_name ),
			'remove_featured_image' => __( 'Remove featured image', parent::$plugin_name ),
			'use_featured_image'    => __( 'Use as featured image', parent::$plugin_name ),
			'insert_into_item'      => __( 'Insert into ad unit', parent::$plugin_name ),
			'uploaded_to_this_item' => __( 'Uploaded to this ad unit', parent::$plugin_name ),
			'items_list'            => __( 'Ad Unit list', parent::$plugin_name ),
			'items_list_navigation' => __( 'Ad Units list navigation', parent::$plugin_name ),
			'filter_items_list'     => __( 'Filter Ad Units list', parent::$plugin_name ),
		);
		$args = array(
			'label'                 => __( 'Ad Unit', parent::$plugin_name ),
			'description'           => __( 'Single Display Ad', parent::$plugin_name ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'revisions', ),
			'taxonomies'            => array( 'adgroup' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 100,
			'menu_icon'             => 'dashicons-money',
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'page',
		);
		register_post_type( self::$post_type, $args );
	}

	public function get_meta_boxes() {
		$metaboxes = array(
			'banner_options' => array(
				'placement' => 'normal',
				'priority'  => 'high',
				'title'     => __( 'Banner Options', parent::$plugin_name ),
				'post_type' => array( self::$post_type ),
				'fields' => array(
					'adunit_image' => array(
						'sanitize' => 'esc_url_raw',
						'value'    => null,
					),
					'adunit_link' => array(
						'sanitize' => 'esc_url_raw',
						'value'    => null,
					),
					'adunit_target' => array(
						'sanitize' => 'sanitize_text_field',
						'value'    => null,
					),
					'adunit_code'   => array(
						'sanitize' => 'trim',
						'value'    => null,
					),
				),
			),
			'code_options' => array(
				'placement' => 'normal',
				'priority'  => 'high',
				'title'     => __( 'Code Options', parent::$plugin_name ),
				'post_type' => array( self::$post_type ),
				'fields' => array(
					'adunit_code'   => array(
						'sanitize' => 'trim',
						'value'    => null,
					),
				),
			),
			'display_options' => array(
				'placement' => 'normal',
				'priority'  => 'high',
				'title'     => __( 'Display Options', parent::$plugin_name ),
				'post_type' => array( self::$post_type ),
				'fields' => array(
					'show_on_pages' => array(
						'sanitize' => null,
						'value'    => array(),
					),
					'show_on_cats' => array(
						'sanitize' => null,
						'value'    => array(),
					),
					'show_on_tags' => array(
						'sanitize' => null,
						'value'    => array(),
					),
					'hide_on_pages' => array(
						'sanitize' => null,
						'value'    => array(),
					),
					'hide_on_cats' => array(
						'sanitize' => null,
						'value'    => array(),
					),
					'hide_on_tags' => array(
						'sanitize' => null,
						'value'    => array(),
					),
				),
			),
		);
		return $metaboxes;
	}

	public function get_display_options_args( $args ) {
		$args['options'] = array(
			'pages'      => get_pages( array( 'post_status' => 'publish' ) ),
			'categories' => get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) ),
			'post_tags'  => get_terms( array( 'taxonomy' => 'post_tag', 'hide_empty' => false ) ),
		);
		return $args;
	}

	public function update_display_rules( $atts ) {
		$args = array(
			'type'      => null, // Possible values = "post" or "term"
			'adunit'    => null, // Adunit post ID
			'relations' => array(),
			'rule'      => null,
		);
		// post_id, $values
		foreach( $atts['values'] as $name => $values ) {
			switch( $name ) {
				case 'show_on_pages' :
					$args = array(
						'type'      => 'page',
						'adunit'    => $atts['post_id'],
						'relations' => $values,
						'rule'      => 'show',
					);
					break;
				case 'show_on_cats' :
					$args = array(
						'type'      => 'category',
						'adunit'    => $atts['post_id'],
						'relations' => $values,
						'rule'      => 'show',
					);
					break;
				case 'show_on_tags' :
					$args = array(
						'type'      => 'post_tag',
						'adunit'    => $atts['post_id'],
						'relations' => $values,
						'rule'      => 'show',
					);
					break;
				case 'hide_on_pages' :
					$args = array(
						'type'      => 'page',
						'adunit'    => $atts['post_id'],
						'relations' => $values,
						'rule'      => 'hide',
					);
					break;
				case 'hide_on_cats' :
					$args = array(
						'type'      => 'category',
						'adunit'    => $atts['post_id'],
						'relations' => $values,
						'rule'      => 'hide',
					);
					break;
				case 'hide_on_tags' :
					$args = array(
						'type'      => 'post_tag',
						'adunit'    => $atts['post_id'],
						'relations' => $values,
						'rule'      => 'hide',
					);
					break;
				default :
					break;
			}
			\Mdm\AdsManager\Database::update_display_rules( $args );
		}
	}

	public function delete_post( $pid ) {
		\Mdm\AdsManager\Database::remove_ads( $pid );
	}
}