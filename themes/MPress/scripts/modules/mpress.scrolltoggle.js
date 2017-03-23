/**
 * Scroll Toggle Module
 * Used for triggering elements based on scroll position
 * @TODO Place github URI to this file
 * @since 12/8/2016
 * @version 1.0.0
 * @package Mpress
 */

( function( $, Mpress ) {
    'use strict';

    var $targets, $elements, transitionEnd;

    var init = function() {
    	transitionEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend transitionend';
        $targets      = $( '.scrolltoggle' );
        $elements = $.map( $targets, function( el ) {
            return new Toggle( $( el ) );
        });
        return {
            elements : $elements,
        };
    };

    function Toggle( $el ) {
    	var $trigger, offset, states, antistates;

    	offset = $el.data( 'trigger-offset' ) || 0;

    	// Classes to add during a transition
    	states = {
    		'up' : {
    			'transition' : 'scrollingUp',
    			'complete'   : 'scrollUp',
    		},
    		'down' : {
    			'transition' : 'scrollingDown',
    			'complete'   : 'scrollDown',
    		},
    	};

    	// Classes to remove during a transition
    	antistates = {
    		'up' : {
    			'transition' : 'scrollDown scrollingDown scrollUp',
    			'complete'   : 'scrollDown scrollingDown scrollingUp',
    		},
    		'down' : {
    			'transition' : 'scrollDown scrollingUp scrollUp',
    			'complete'   : 'scrollingDown scrollingUp scrollUp',
    		},
    	};

    	var cacheDom = function() {
    		$trigger = $( $el.data( 'trigger' ) );
    		$trigger = $trigger.length ? $trigger : $el;
    	};

    	var bindEvents = function() {
    		$trigger.waypoint( scrollTrigger, { offset : offset } );
    	};

    	var scrollTrigger = function( direction ) {
    		$el.trigger( 'scrolltoggle:start', [{ direction : direction }] );
    		// Add classes to individual elements
    		$el.stop().removeClass( antistates[direction].transition ).addClass( states[direction].transition ).one( transitionEnd, function() {
    		    $el.addClass( states[direction].complete ).removeClass( antistates[direction].complete );
    		});
    		// Set timeout in case ending events done fire
    		setTimeout( function() {
    		    // If the class we need to remove was already removed by something else, let's remove the handler and bail
    		    if( !$el.hasClass( states[direction].transition ) ) {
    		        $el.off( transitionEnd );
    		        return;
    		    }
    		    // If we made it here, we have some cleanup to do
    		    $el.addClass( states[direction].complete ).removeClass( antistates[direction].complete );
    		}, 1000 );
    		$el.trigger( 'scrolltoggle:end', [{ direction : direction }] );
    		return;
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
    Mpress.scrollToggle = init();
})(jQuery, Mpress );