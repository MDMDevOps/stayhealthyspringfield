<?php
/*******************************************************************************
 *                 ______                 __  _
 *                / ____/_  ______  _____/ /_(_)___  ____  _____
 *               / /_  / / / / __ \/ ___/ __/ / __ \/ __ \/ ___/
 *              / __/ / /_/ / / / / /__/ /_/ / /_/ / / / (__  )
 *             /_/    \__,_/_/ /_/\___/\__/_/\____/_/ /_/____/
 *
 ******************************************************************************/

/**
 * Functions
 * Boostrap all custom functions that our theme needs to run
 * @package mpress-child
 * @see     https://codex.wordpress.org/Functions_File_Explained
 * @since   version 1.0.0
 */

/**
 * Define constants
 * Must be different than the constants used in the MPRESS PARENT
 * @since version 1.0.1
 */

define( 'CHILD_THEME_ROOT_DIR', trailingslashit( get_stylesheet_directory() ) );
define( 'CHILD_THEME_ROOT_URI', trailingslashit( get_stylesheet_directory_uri() ) );

/* -------------------------------------------------------------------------- */

/**
 * HERE YOU CAN PASTE FUNCTIONS YOU WANT TO REPLACE, OR ADD FILTERS / ACTIONS TO SUPPLEMENT EXISTING FUNCTIONS
 * AS TIME GOES ON, WE WILL BEGIN ADDING FILTER / ACTION HOOKS TO ALL FUNCTIONS, SO THEY WON'T NEED REPLACED
 */

 /**
 * Hook into parent theme stylesheet uri. If registering addition stylesheets, can add the dependencies here
 * Parent theme handles enqueueing styles, child replaces URI of stylesheet so both aren't enqueued
 * If both are desired, replace with standard register / enqueue function
 * @since version 1.0.0
 */
if ( !function_exists( 'register_public_stylesheets' ) ) {
    function register_public_stylesheets( $stylesheets ) {
    	// Enqueue Google Fonts
    	$stylesheets['google-fonts'] = array(
    		'src'   => '//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Lato:300,400,400i,700',
    		'deps'  => array(),
    		'ver'   => '1.0.0',
    		'media' => 'all',
    	);
    	// Enqueue main stylesheet from child instead of parent
    	$stylesheets['mpress-public'] = array(
    		'src'   => sprintf( '%1$sstyles/dist/public.min.css', CHILD_THEME_ROOT_URI ),
    		'deps'  => array( 'FontAwesome', 'google-fonts' ),
    		'ver'   => '1.0.0',
    		'media' => 'all',
    	);
    	return $stylesheets;
    }
    add_filter( 'mpress_public_stylesheets', 'register_public_stylesheets' );
}
/* -------------------------------------------------------------------------- */

function register_editor_stylesheets( $stylesheets ) {
	$stylesheets[] = sprintf( '%1$sstyles/dist/editor.min.css', CHILD_THEME_ROOT_URI );
	return $stylesheets;
}
add_filter( 'mpress_editor_stylesheets', 'register_editor_stylesheets' );

/* -------------------------------------------------------------------------- */

function register_public_scripts( $scripts ) {
	$child_scripts = array(
		'venture-public' => array(
			'src'    => sprintf( '%1$sscripts/dist/public.min.js', CHILD_THEME_ROOT_URI ),
			'deps'   => array( 'jquery', 'mpress-public' ),
			'ver'    => '1.0.0',
			'footer' => true,
		),
	);
	return array_merge( $scripts, $child_scripts );
}
add_filter( 'mpress_public_scripts', 'register_public_scripts' );

/**
 * Register Widget Area(s)
 * Override or extend Parent Widget Areas
 * @since version 1.0.0
 * @see   http://codex.wordpress.org/Widgetizing_Themes
 */
if ( !function_exists( 'mpress_add_sidebars' ) ) {
    function register_child_sidebars( $sidebars ) {
    	$child_sidebars = array(
    		'masthead' => array(
    			'name'          => __( 'Masthead Widgets', 'mpress-child' ),
    			'id'            => 'masthead-widget-area',
    			'before_widget' => '<div id="%1$s" class="widget group %2$s">',
    			'after_widget'  => "</div>",
    			'before_title'  => '<h4 class="widget-title">',
    			'after_title'   => '</h4>'
    		),
    		'masthead-home' => array(
    			'name'          => __( 'Homepage Masthead Widgets', 'mpress-child' ),
    			'id'            => 'homepage-masthead-widget-area',
    			'before_widget' => '<div id="%1$s" class="widget group %2$s">',
    			'after_widget'  => "</div>",
    			'before_title'  => '<h4 class="widget-title">',
    			'after_title'   => '</h4>'
    		),

    		'after-content-widget-area' => array(
    			'name'          => __( 'After Content Widgets', 'mpress-child' ),
    			'id'            => 'after-content-widget-area',
    			'before_widget' => '<div id="%1$s" class="widget %2$s">',
    			'after_widget'  => "</div>",
    			'before_title'  => '<h4 class="widget-title">',
    			'after_title'   => '</h4>'
    		),
    		'widget-bar' => array(
    			'name'          => __( 'Widget Bar', 'mpress-child' ),
    			'id'            => 'widget-bar',
    			'before_widget' => '<div id="%1$s" class="widget %2$s">',
    			'after_widget'  => "</div>",
    			'before_title'  => '<h4 class="widget-title screen-reader-text">',
    			'after_title'   => '</h4>'
    		),
    		'footer-left-column' => array(
    			'name'          => __( 'Footer Left Column', 'mpress-child' ),
    			'id'            => 'footer-left-column',
    			'before_widget' => '<div id="%1$s" class="widget %2$s">',
    			'after_widget'  => "</div>",
    			'before_title'  => '<h4 class="widget-title">',
    			'after_title'   => '</h4>'
    		),
    		'footer-center-column' => array(
    			'name'          => __( 'Footer Center Column', 'mpress-child' ),
    			'id'            => 'footer-center-column',
    			'before_widget' => '<div id="%1$s" class="widget %2$s">',
    			'after_widget'  => "</div>",
    			'before_title'  => '<h4 class="widget-title">',
    			'after_title'   => '</h4>'
    		),
    		'footer-right-column' => array(
    			'name'          => __( 'Footer Right Column', 'mpress-child' ),
    			'id'            => 'footer-right-column',
    			'before_widget' => '<div id="%1$s" class="widget %2$s">',
    			'after_widget'  => "</div>",
    			'before_title'  => '<h4 class="widget-title">',
    			'after_title'   => '</h4>'
    		),
    	);
    	return array_merge( $sidebars, $child_sidebars );
    } // end wpstock_widgets_init
    add_filter( 'mpress_sidebars', 'register_child_sidebars' );
} // endif
/* -------------------------------------------------------------------------- */

if( !function_exists( 'register_child_menus' ) ) {
	function register_child_menus( $menus ) {
		$menus = array(
			'primary-navbar'  => __( 'Primary Nav Bar', 'mpress-child' ),
		);
		return $menus;
	}
	add_filter( 'mpress_menus', 'register_child_menus' );
};


function add_font_icons( $icons ) {
	$icons['scrolltop'] = 'icon fa fa-angle-up fa-fw';
	return $icons;
}
add_filter( 'mpress_font_icons', 'add_font_icons' );

function extend_mdm_recent_posts( $templates ) {
	$theme_templates = array(
		'Featured Post Cards' => CHILD_THEME_ROOT_DIR . 'views/featured_posts.php',
	);
	return array_merge( $templates, $theme_templates );
}
add_filter( 'mdm_recent_posts_templates', 'extend_mdm_recent_posts' );

function add_custom_image_sizes() {
	add_image_size( 'featured-post-thumbnail', 570, 320, true );
}
add_action( 'after_setup_theme', 'add_custom_image_sizes' );

function show_single_category() {
	$cats = get_the_category();
	$current = current( $cats );
	echo $current->name;
}
add_action( 'show_single_category', 'show_single_category' );

function logo_shortcode() {
	return get_custom_logo();
}
add_shortcode( 'the_custom_logo', 'logo_shortcode' );

function categories_use_page_header( $url ) {
	if( !is_single() ) {
		return $url;
	}
	// Get the categoris
	$categories = get_the_category();
	if( empty( $categories ) ) {
		return $url;
	}
	// Grab the first category
	$category = current( $categories );
	// Grab the page that matches that category
	$page = get_page_by_path( $category->name );
	if( empty( $page ) ) {
		return $url;
	}
	$img = get_the_post_thumbnail_url( $page );
	if( !empty( $img ) ) {
		return $img;
	}
	// Finally default to returning URL
	return $url;
}
add_filter( 'replace_featured_image_as_header', 'categories_use_page_header' );

function thank_you_page_headers( $url ) {
	// If we're not on a single page
	if( !is_page() ) {
		return $url;
	}
	if( !isset( $_GET['gfoptin'] ) ) {
		return $url;
	}
	if( isset( $_GET['type'] ) && $_GET['type'] === 'page' ) {
		if( isset( $_GET['post_id'] ) )
		// var_dump($page);
		$img = get_the_post_thumbnail_url( intval( $_GET['post_id'] ) );
		if( !empty( $img ) ) {
			return esc_url_raw( $img );
		}
	}
	return $url;

}
add_filter( 'replace_featured_image_as_header', 'thank_you_page_headers' );
// Gravity Forms Fields
function populate_gforms_post_type_fields( $value ) {
    return get_post_type();
}
add_filter( 'gform_field_value_post_type', 'populate_gforms_post_type_fields' );

function populate_gforms_post_name_fields( $value ) {
	global $wp_query;
    return $wp_query->queried_object->ID;
}
add_filter( 'gform_field_value_post_id', 'populate_gforms_post_name_fields' );

function populate_gforms_category_fields( $value ) {
	// Get the categoris
	$categories = get_the_category();
	if( empty( $categories ) ) {
		return $value;
	}
	// Grab the first category
	$category = current( $categories );
    return $category->name;
}
add_filter( 'gform_field_value_category', 'populate_gforms_category_fields' );

function place_rev_slider( $alias ) {
	if( class_exists( 'RevSlider' ) ) {
		// Instantiate rev slider
		$rev_slider = new RevSlider();
		// Get all aliases
		$sliders = $rev_slider->getAllSliderAliases();
		// If alias exists, place it
		if( in_array( trim( $alias ), $sliders ) ) {
			putRevSlider( $alias );
		}
	}
}
add_action( 'place_rev_slider', 'place_rev_slider' );