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
 * Set Content Width
 * @since version 1.0.0
 * @see   https://codex.wordpress.org/Content_Width
 */
if ( !isset( $content_width ) ) {
    $content_width = 1280;
}
/* -------------------------------------------------------------------------- */


if( !function_exists( 'mpress_theme_engine_ignition_sequence' ) ) {
    function mpress_theme_engine_ignition_sequence() {
    	// If version is less than minimum, register notice
    	if( version_compare( '5.3.0', phpversion(), '>=' ) ) {
    		return;
    	}
    	// Register Autoloader
    	spl_autoload_register( 'mpress_autoload_register' );
    	// Instantiate our plugin
    	$mpress = call_user_func( array( '\\Mpress\\Theme', 'get_instance' ) );
    	// And finally, run it...
    	$mpress->burn_baby_burn();
    }
    mpress_theme_engine_ignition_sequence();
}

function mpress_autoload_register( $className ) {
	// Check and make damned sure we're only loading things from this namespace
	if( strpos( $className, 'Mpress' ) === false ) {
		return;
	}
	// replace backslashes
	$className = strtolower( str_replace( '\\', '/', $className ) );
	// Ensure there is no slash at the beginning of the classname
	$className = ltrim( $className, '/' );
	// Append full path to class
	$path  = trailingslashit( get_template_directory() ) . 'includes/' . "{$className}.php";
	// Replace some known constants
	$path = str_replace( 'mpress/', '', $path );
	// include the class...
	include_once( $path );
}