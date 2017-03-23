/**
 * Jumpscroll module
 * Used for smooth, animated jumpscrolling within a page
 * @TODO Place github URI to this file
 * @since 11/27/2016
 * @version 1.0.0
 * @package Mpress
 */

( function( $, Mpress ) {
    'use strict';

    var targets, scrollSpeed, scrollOffset, jumplinks;

    var init = function() {
        targets      = $( 'a.jumpscroll, li.jumpscroll > a' );
        scrollSpeed  = null;
        scrollOffset = null;
        jumplinks = $.map( targets, function( el ) {
            return new JumpLink( el );
        });
        return {
            speed : scrollSpeed,
            offset : setOffset,
            extend : extend,
            elements : jumplinks
        };
    };

    var setOffset = function( input ) {
    	scrollOffset = parseInt( input );
    };

    var extend = function( el ) {
        $.map( $( el ), function( el ) {
            module.elements.push( new JumpLink( el ) );
        });
    };

    // Define a single jumplink object
    function JumpLink( el ) {
        var $el, $hash, $target;
        /**
         * cache DOM elements and options
         * Set variables and assign DOM elements to reusable vars
         * @return (null) blank return to maintain consitancy
         */
        var cacheDom = function() {
            $el     = $( el );
            $hash   = el.hash !== '' ? $( el.hash ) : $( 'body' );
            $target = $hash.length !== 0 ? $hash : $( 'body' );
            return;
        };

        /**
         * Binds events
         * @return (null) blank return to maintain consitancy
         */
        var bindEvents = function() {
            $el.on( 'click', scroll );
            return;
        };

        /**
         * Get scroll speed option in this order:
         * 1. If elements has data-scroll-speed set, use that.
         * 2. Else, if scrollspeed option is set globally, use that.
         * 3. Else, if able calculate distance and use progressive speed
         * 4. Final fallback, just use 'slow'
         * @return (int or string) Speed at which to scroll
         */
        var getScrollSpeed = function() {
        	return 'slow';
            //return $el.data( 'scroll-speed' ) || scrollSpeed || Math.abs( window.pageYOffset - $target.offset().top ) / 2 || 'slow';
        };

        /**
         * Get scroll offest option in this order:
         * 1. If elements has data-scroll-offset set, use that.
         * 2. Else, if scrollOffset option is set globally, use that.
         * 3. Final fallback, just use 0 offset
         * @return (int or string) Offset to use
         */
        var getScrollOffset = function() {
        	console.log( scrollOffset );
            var offset = $el.data( 'scroll-offset' ) || scrollOffset || 0;
            return $target.offset().top - parseInt( offset );
        };

        /**
         * How to handle scroll events
         * First, capture event to prevent page reloading
         * Then, scroll page to specified point using options
         * Last, blur the link focus and return self
         * @param  (object) event : Event object
         * @return ( object ) $el : return self for chaining
         */
        var scroll = function( event ) {
            event.preventDefault();
            $el.trigger( 'jumpscroll:start' );
            $( 'body, html' ).stop().animate( { scrollTop : getScrollOffset() }, getScrollSpeed(), function() {
                $el.trigger( 'jumpscroll:done' );
            });
            $el.blur();
            return $el;
        };

        /**
         * Anonomous init function
         * Organize execution of module
         * @type self invoking function to kickoff the module
         */
        ( function() {
            cacheDom();
            bindEvents();
        })();
        return $el;
    }

    /**
     * Attach module to Mpress global
     */
    Mpress.jumpscroll = init();
})(jQuery, Mpress );