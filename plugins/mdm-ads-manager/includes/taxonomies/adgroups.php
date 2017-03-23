<?php

namespace Mdm\AdsManager\Taxonomies;

use \Mdm\AdsManager\Utilities as Utilities;

class AdGroups extends \Mdm\AdsManager {

	public static function register() {
		$labels = array(
			'name'                       => _x( 'Ad Groups', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Ad Group', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Ad Groups', 'text_domain' ),
			'all_items'                  => __( 'All Ad Groups', 'text_domain' ),
			'parent_item'                => __( 'Parent Ad Group', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Ad Group:', 'text_domain' ),
			'new_item_name'              => __( 'New Ad Group Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Ad Group', 'text_domain' ),
			'edit_item'                  => __( 'Edit Ad Group', 'text_domain' ),
			'update_item'                => __( 'Update Ad Group', 'text_domain' ),
			'view_item'                  => __( 'View Ad Group', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove ad groups', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used ad groups', 'text_domain' ),
			'popular_items'              => __( 'Popular Ad Groups', 'text_domain' ),
			'search_items'               => __( 'Search Ad Groups', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No Ad Groups', 'text_domain' ),
			'items_list'                 => __( 'Ad Groups list', 'text_domain' ),
			'items_list_navigation'      => __( 'Ad Groups list navigation', 'text_domain' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_in_menu'               => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'show_in_rest'               => true,
		);
		register_taxonomy( 'adgroup', array( 'adunit' ), $args );
	}
}