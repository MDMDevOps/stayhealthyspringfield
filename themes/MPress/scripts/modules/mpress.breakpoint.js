/**
 * Breakpoint module
 * Used to measure breakpoints using css
 * @TODO Place github URI to this file
 * @since 11/27/2016
 * @version 1.0.0
 * @package Mpress
 */

( function( $, Mpress ) {
    'use strict';
    var $mark;

    var init = function() {
        // Bind function to kickoff ass soon as document is ready
        $( document ).ready( cacheDom );
        // Return function
        return {
            get : get
        };
    };

    var cacheDom = function() {
        $mark = $( '#makers-mark' );
    };

    // Determine the breakpoint
    var get = function() {
        // One last chance to find mark if not yet defined
        if( typeof $mark === 'undefined' ) {
            cacheDom();
        }
        if( $mark.length === 0 ) {
            return false;
        }
        return $mark.css( 'opacity' ) * 10;
    };

    /**
     * Attach module to Mpress global
     */
    Mpress.breakpoint = init();
})(jQuery, Mpress );