<?php

/**
 * Page Specific Menus
 * @since 2.0.0
 * @see https://codex.wordpress.org/Custom_Headers
 * @todo document module, add link here to wiki page
 * @package mpress
 */

class Venture_Page_Specific_Menus extends Mpress_Theme_Engine implements Mpress_Theme_Module {

	/**
	 * Run the module
	 * Defines exactly how the module should be setup, and kickoff operations
	 * @since 2.0.0
	 */
	public function ignite() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 3 );
		add_action( 'page_specific_menu', array( $this, 'output_menu' ) );
	}

	public function add_metabox() {
		add_meta_box( 'page_specific_menu',  __( 'Page Menu', 'mpress-child' ), array( $this, 'display_metabox' ),  array( 'page' ), 'normal', 'high', null );
	}

	public function display_metabox( $post ) {
		// Get all menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		// Get menu for this specific page
		$selected = get_post_meta( $post->ID, 'page_specific_menu', true );
		// Include nonce for security
		wp_nonce_field( 'page_specific_menu_meta_nonce', 'page_specific_menu_nonce' );
		// Include markup
		include CHILD_THEME_ROOT_DIR . 'views/metaboxes/page_specific_menu.php';
	}

	public function save_metabox( $post_id, $post ) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['page_specific_menu_nonce'] ) || !wp_verify_nonce( $_POST['page_specific_menu_nonce'], 'page_specific_menu_meta_nonce' ) ) {
			return;
		}
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		update_post_meta( $post_id, 'page_specific_menu', sanitize_text_field( $_POST['page_specific_menu'] ) );
	}

	public function output_menu( $args = array() ) {
		// Get meta value
		$menu_name = get_post_meta( get_the_id(), 'page_specific_menu', true );
		// If no menu, their is nothing to do...

		if( empty( $menu_name ) ) {
			return;
		}
		$args = array(
			'theme_location'  => '',
			'menu'            => $menu_name,
			'container'       => 'nav',
			'container_class' => $menu_name,
			'container_id'    => 'page-specific-navigation',
			'menu_class'      => 'page-specific-menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '<span class="button">',
			'after'           => '</span>',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		);
		wp_nav_menu( $args );
	}
}