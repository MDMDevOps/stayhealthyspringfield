<?php

namespace Mdm\Recent_Posts\Models;

class Utilities extends \Mdm\Recent_Posts {

	/**
	 * Helper function to use relative URLs
	 * @since 1.0.0
	 */
	public static function url( $url = '' ) {
		return parent::$plugin_url . ltrim( $url, '/' );
	}

	/**
	 * Helper function to use relative paths
	 * @since 1.0.0
	 */
	public static function path( $path = '' ) {
		return parent::$plugin_path . ltrim( $path, '/' );
	}

	/**
	 * Helper function to print debugging statements to the window
	 * @since 1.0.0
	 */
	public static function expose( $expression ) {
		echo '<pre class="expose">';
		print_r( $expression );
		echo '</pre>';
	}

	public static function get_templates() {
		// Allow themes / plugins / etc to add templates
		$templates = apply_filters( 'mdm_recent_posts_templates', array() );
		// Merge with default template, prepending default to the front of the array
		$templates = wp_parse_args( array( 'Default' => self::path( 'templates/default.php' ) ), $templates );
		// Return
		return $templates;
	}

	public static function normalize_bool_values( $values ) {
		// If passed a single value...
		if( is_string( $values ) ) {
			return self::normalize_bool_value( $values );
		}
		// If array
		if( is_array( $values ) ) {
			foreach( $values as $index => $value ) {
				$values[$index] = self::normalize_bool_value( $value );
			}
		}
		return $values;
	}

	private static function normalize_bool_value( $value ) {
		// If isn't a string, return
		if( !is_string( $value ) ) {
			return $value;
		}
		// If is string value of true
		if( strtolower( $value ) === 'true' ) {
			return true;
		}
		// If is string value of false
		if( strtolower( $value ) === 'false' ) {
			return false;
		}
		// Finally, just return value if we made it here
		return $value;
	}

	/**
	 * Parse terms (categories, tags, etc) from a string
	 * Must be in the format of an array for wp_query
	 * Allow terms to be passed as a string of names instead of proper id's
	 @return array
	 */
	public static function parse_terms_from_string( $terms = '', $term_type = 'category' ) {
	    // If it is not a string that needs parsed, just return it
	    if( !is_string( $terms ) ) {
	        return $terms;
	    }
	    // Create empty array to hold IDs
	    $term_ids = array();
	    // Get ids for each
	    foreach( explode( ',', $terms ) as $term ) {
	        // Get the term from the database
	        $term_object = get_term_by( 'slug', trim( $term ), $term_type );
	        if( $term_object ) {
	        	// Push to list
	        	$term_ids[] = $term_object->term_id;
	        }
	    }
	    return $term_ids;
	}

}