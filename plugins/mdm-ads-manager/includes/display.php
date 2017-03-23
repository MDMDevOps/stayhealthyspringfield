<?php

namespace Mdm\AdsManager;

use \Mdm\AdsManager\Utilities as Utilities;

class Display extends \Mdm\AdsManager {

	public function register_assets() {
		wp_register_style( sprintf( '%s_display',  parent::$plugin_name ), Utilities::url( 'styles/dist/display.min.css' ), array(),  parent::$plugin_version, 'all' );
		wp_register_script( sprintf( '%s_display', parent::$plugin_name ), Utilities::url( 'scripts/dist/display.min.js' ), array( 'jquery' ), parent::$plugin_version, true );
	}

	/**
	 * Enqueue admin side assets
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
	 * @see https://developer.wordpress.org/reference/functions/wp_localize_script/
	 */
	public function enqueue_assets() {
		wp_enqueue_style( sprintf( '%s_display',  parent::$plugin_name ) );
		wp_enqueue_script( sprintf( '%s_display',  parent::$plugin_name ) );
		wp_localize_script( sprintf( '%s_display', parent::$plugin_name ), parent::$plugin_name, array( 'wpajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function adgroup_display( $atts = array() ) {
		echo $this->get_adgroup_display( $atts );
	}

	public function get_adgroup_display( $atts = array() ) {
		$default_atts = array(
			'adgroup'   => null,
			'showposts' => 1,
		);
		// Parse atts
		$atts = shortcode_atts( $default_atts, $atts, 'display_adgroup' );
		if( empty( $atts['adgroup'] ) ) {
			return false;
		}
		// Get eligable Ads for this page/category/archive
		$eligable_ads = $this->get_eligable_ads();
		// Allow filters to modify eligable ads
		$eligable_ads = apply_filters( 'eligable_ads', $eligable_ads );
		// If we have ads for the page
		if( empty( $eligable_ads ) ) {
			$eligable_ads = $this->get_default_ads();
		}
		if( !empty( $eligable_ads ) ) {
			$posts = \Mdm\AdsManager\Posts::get_instance();
			// Get all ads that belong to adgroup
			$eligable_ads = $posts->get_all( array( 'post_type' => array( 'adunit' ), 'post__in' => $eligable_ads, 'tax_query' => array( array( 'taxonomy' => 'adgroup', 'field' => 'id', 'terms' => $atts['adgroup'] ) ) ) );
			// Recheck for default ads, since the grouping may have caused them to return none
			if( empty( $eligable_ads ) ) {
				$eligable_ads = $this->get_default_ads();
				if( !empty( $eligable_ads  ) ) {
					$eligable_ads = $posts->get_all( array( 'post_type' => array( 'adunit' ), 'post__in' => $eligable_ads, 'tax_query' => array( array( 'taxonomy' => 'adgroup', 'field' => 'id', 'terms' => $atts['adgroup'] ) ) ) );
				}
			}
			if( !empty( $eligable_ads ) ) {
				$output = sprintf( '<div id="adgroup-%s">', $atts['adgroup'] );
				for( $i = 0; $i < 10; $i++ ) {
					if( !isset( $eligable_ads[$i] ) ) {
						break;
					}
					$output .= $this->get_single_display( $eligable_ads[$i]->ID );
				}
				$output .= '</div>';
				return $output;
			}
		}
		return false;
	}

	public function single_display( $id = null ) {
		echo $this->get_single_display( $id );
	}

	public function get_single_display( $id = null ) {
		if( empty( $id ) ) {
			return false;
		}
		// adunit_image
		$banner = get_post_meta( $id, 'banner_options', true );
		$code   = get_post_meta( $id, 'code_options', true );
		// Start output
		$output = sprintf( '<div id="ad-id-%s" class="ad-manager">', $id );
		// If there is an image...
		if( !empty( $banner['adunit_image'] ) ) {
			$data    = getimagesize( $banner['adunit_image'] );
			$output .= '<div class="ad-manager-banner">';
			$output .= sprintf( '<a href="%s" data-ad="%d" target="%s"><img src="%s" %s></a>', esc_url_raw( $banner['adunit_link'] ), intval( $id ), esc_attr( $banner['adunit_target'] ), esc_url_raw( $banner['adunit_image'] ), $data[3] );
			$output .= '</div>';
		}
		// If there id code
		if( !empty( $code['adunit_code'] ) ) {
			$output .= '<div class="ad-manager-code">';
			$output .= apply_filters( 'the_content', $code['adunit_code'] );
			$output .= '</div>';
		}
		$output .= '</div>';
		return $output;
	}

	private function get_eligable_ads() {
		$ads  = array();
		$ids  = array();
		// See if we're getting ads for a page
		if( is_page() ) {
			$ads = $this->get_page_ads( get_the_id() );
		}
		// Get ads for a single post
		elseif( is_single() ) {
			// Get Category Based Ads
			// $ads = $this->get_terms_ads( 'category' );
			$categories = get_the_category();
			if( !empty( $categories ) ) {
				foreach( $categories as $category ) {
					$ads = array_merge( $ads, $this->get_term_ads( $category->term_id, 'category' ) );
				}
			}
			// If still empty, do the same for tags
			if( empty( $ads ) ) {
				$tags = get_the_tags();
				if( !empty( $tags ) ) {
					foreach( $tags as $tag ) {
						$ads = array_merge( $ads, $this->get_term_ads( $tag->term_id, 'post_tag' ) );
					}
				} //$query = array( 'rule' => 'show', 'relation_type' => $term_type, 'relation_id' => -1 );
			}
		}
		// Archive pages
		elseif( is_archive() ) {
			// Do stuff here for generic archives...TODO
			// Category Archive
			if( is_category() ) {
				$cat_name = single_cat_title( '', false );
				if( !empty( $cat_name ) ) {
					$cat_id = get_cat_ID( $cat_name );
					$ads = $this->get_term_ads( $cat_id, 'category' );
				}
			}
			// Tag archive
			elseif( is_tag() ) {
				$tag_name = single_tag_title( '', false );
				if( !empty( $tag_name ) ) {
					$tag_id = get_tag_ID( $tag_name );
					$ads = $this->get_term_ads( $tag_id, 'post_tag' );
				}
			}
		}

		foreach( $ads as $index => $ad ) {
			$ids[] = intval( $ad->adunit_id );
		}
		return $ids;
	}

	public function get_default_ads() {
		$ads  = array();
		$ids  = array();
		// See if we're getting ads for a page
		if( is_page() ) {
			$ads = $this->get_page_ads( -1 );
		}
		// Get ads for a single post
		elseif( is_single() ) {
			$ads = $this->get_term_ads( -1, 'category' );
			if( empty( $ads ) ) {
				$ads = $this->get_term_ads( -1, 'post_tag' );
			}
		}
		// Archive pages
		elseif( is_archive() ) {
			// Do stuff here for generic archives...TODO
			// Category Archive
			if( is_category() ) {
				$ads = $this->get_term_ads( -1, 'category' );
			}
			// Tag archive
			elseif( is_tag() ) {
				$ads = $this->get_term_ads( -1, 'post_tag' );
			}
		}
		foreach( $ads as $index => $ad ) {
			$ids[] = intval( $ad->adunit_id );
		}
		return $ids;
	}

	private function get_term_ads( $term_id, $term_type ) {
		$query = array( 'rule' => 'show', 'relation_type' => $term_type, 'relation_id' => $term_id );
		$ads = \Mdm\AdsManager\Database::get_eligable_ads( $query );
		return $ads;
	}

	private function get_page_ads( $page_id ) {
		$query = array( 'rule' => 'show', 'relation_type' => 'page', 'relation_id' => $page_id );
		$ads = \Mdm\AdsManager\Database::get_eligable_ads( $query );
		// See if we have an add set to "any" page
		return $ads;
		// $ads = array();
		// $query = array( 'rule' => 'show', 'relation_type' => 'page', 'relation_id' => get_the_id() );
		// $ads = \Mdm\AdsManager\Database::get_eligable_ads( $query );
		// // If it's empty, see if we have one specified for "any"
		// if( empty( $ads ) ) {
		// 	$query = array( 'rule' => 'show', 'relation_type' => 'page', 'relation_id' => -1 );
		// 	$ads = \Mdm\AdsManager\Database::get_eligable_ads( $query );
		// }
	}

	private function get_terms_ads( $term_type ) {
		$terms = ( $term_type === 'category' ) ? get_the_category() : get_the_tags();
		$ads   = array();
		if( empty( $terms ) ) {
			return $ads;
		}
		foreach( $terms as $term ) {
			$query = array( 'rule' => 'show', 'relation_type' => $term_type, 'relation_id' => $term->term_id );
			$ads = array_merge( \Mdm\AdsManager\Database::get_eligable_ads( $query ), $ads );
		}
		if( empty( $ads ) ) {
			$query = array( 'rule' => 'show', 'relation_type' => $term_type, 'relation_id' => -1 );
			$ads = \Mdm\AdsManager\Database::get_eligable_ads( $query );
		}
		return $ads;
	}

}