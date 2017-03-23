/**
 * Toggle Buttons
 * Buttons that toggle other elements, such as opening menus
 * @TODO Place github URI to this file
 * @since 11/27/2016
 * @version 1.0.0
 * @package Mpress
 */

( function( $, Mpress ) {
    'use strict';
    var $body, $elements;

    var init = function() {
        $body = $( 'body' );
        $elements = $.map( $( '.menu-toggle' ), function( element ) {
            return new Button( $( element ) );
        });
        /**
         * @TODO Create add/remove functions, so we can adjust options from child js file
         */
        return {};
    };

    /**
     * A single toggle(able) element element definition
     * Defines attributes, and behavior of a togglable element
     * @param {[jquery object]} $el : The targeted object (menu, div, etc)
     */
    function Toggle( $el ) {
    	var states, antistates, options;
        /**
         * Function to handle completion of a toggle event
         * 1. Remove & Add appropriate classes, bases on action
         * 2. Remove jQuery generated inline display style
         * 3. Trigger toggle:done event, for other functions to bind to
         * @param  ( object ) event : The event which triggered the action
         * @return ( object ) $el : Main element, returned for chaining function calls
         */
        var completeToggle = function( event, data ) {
        	console.log(data);

        	// Detach ending transition binding
        	$el.off( options.transitionEnd, completeToggle );
        	// Make sure we have what we need
        	if( typeof event.data === 'undefined' || typeof event.data.action === 'undefined' ) {
        		return $el;
        	}
        	var action = event.data.action;
            // Add / Remove appropriate classes
            $el.removeClass( antistates[action].complete ).addClass( states[action].complete ).removeAttr( 'style' );
            // Trigger done event
            $el.trigger( 'toggle:done', { $el : $el } );
            // Return for chaining
            return $el;
        };

        /**
         * Animate toggle
         * Performs jquery animations of toggle, if any are specified
         * Attached to el to allow chained events / functions
         * @param  (string) action : String indicator of which type of toggle we're doing (show/hide)
         * @return (object) $el : jQuery object, returned so functions can be chained
         */
         $el.animateToggle = function( action ) {
         	if( options[action] ) {
         		$el[ options[action] ]( options.duration, function() {
         			$el.trigger( 'transitionend' );
         		});
         	}
            return $el;
        };

        var toggle = function( event, data ) {
        	var action, aria;
        	action = data.action;
        	// Set aria to either true or false, depending on action
        	aria = ( data.action === 'activate' );
        	// Trigger start event for other functions to bind to
        	$el.trigger( 'toggle:start' );
        	// Perform action
        	$el.finish().attr( 'aria-expanded', aria ).removeClass( antistates[data.action].transition ).addClass( states[data.action].transition ).animateToggle( action );
        	// Attach handler for completion of transition / animation
        	$el.one( options.transitionEnd, { action : action }, completeToggle );
        	// Set timeout to trigger transition end event, in case it hasn't fired after a certain period of time
        	setTimeout( function() {
        	    $el.trigger( 'transitionend', [{ triggerer : 'timeout' }] );
        	}, 1000 );
        };

        /**
         * Anonomous init function
         * Organize execution of module
         * @type self invoking function to kickoff the module
         */
        ( function() {
        	// Set options
        	options = {
        	    activate   : $el.data( 'activate' ) || null,
        	    deactivate : $el.data( 'deactivate' ) || null,
        	    duration      : $el.data( 'duration' ) || 'slow',
        	    transitionEnd : 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend transitionend',
        	};
        	// Classes to add during a transition
        	states = {
        		'activate' : {
        			'transition' : 'opening',
        			'complete'   : 'open',
        		},
        		'deactivate' : {
        			'transition' : 'closing',
        			'complete'   : 'closed',
        		},
        	};

        	// Classes to remove during a transition
        	antistates = {
        		'activate' : {
        			'transition' : 'closing closed open',
        			'complete'   : 'closing closed opening',
        		},
        		'deactivate' : {
        			'transition' : 'opening open closed',
        			'complete'   : 'opening open closing',
        		},
        	};
        	// Bind namespaced toggle event
            $el.on( 'toggle.mpress', toggle );
            $el.on( 'toggle:complete', completeToggle );
        })();

        /**
         * @return (object) $el(ement) so other modules can work chain functions, as well as bind/unbind events
         */
        return $el;
    } // end toggle class

    // Define a single buttons behavior
    function Button( $el ) {
        // Instantiate variables
        var $toggles, $group, count, name;
        /**
         * Cache all DOM elements to their respective variables
         * Sets $toggles to map of all elements with data-toggle="[name]" specified on toggle button
         * @return  blank return, to maintain code consistancy
         */
        var cacheDom = function() {
        	var group = $( '[data-toggle=' + name + ']' );
        	$group = group.map( function() {
                return $( this );
            });
            $toggles = group.not( $( '.menu-toggle' ) ).map( function() {
                return new Toggle( $( this ) );
            });
             return;
        };

         /**
          * Bind event handlers
          * @return  blank return, to maintain code consistancy
          */
        var bindEvents = function() {
            $el.on( 'click', toggleState );
            return;
        };

        /**
         * Handle toggle completion
         * Define what actions to perform when an element reports it's toggle event is done
         * 1. Unbind toggle:done event from single element, passed with data.$el
         * 2. If all single toggle elements have fired done events,
         * @param (json) data : passed in data, including which element (data.$el) this event belong to. Prevents having to create an additional $( event.target ) object
         * @return  blank return, to maintain code consistancy
         */
        var handleCompletion = function( event, data ) {
            // Remove handler from this element
            data.$el.off( 'toggle:done', handleCompletion );
            // Handle completion
            if( ++count === $toggles.length ) {
                if( $el.attr( 'aria-expanded' ) === 'true' ) {
                    $body.removeClass( name + '-opening' ).addClass( name + '-open' );
                }
                else {
                    $body.removeClass( name + '-closing' ).addClass( name + '-closed' );
                }
            }
            return;
        };
         var toggleState = function( event ) {
             var action, newState;
             // Make sure to remove any default behavior from button/link that is clicked
             event.preventDefault();
             // Reset toggle count
             count = 0;
             // Take apprpriate actions based on current state
             if( $el.attr( 'aria-expanded' ) === 'false'  ) {
                 // Define which action to take
                 action = 'activate';
                 // Add class to body
                 $body.removeClass( name + '-closing' + ' ' + name + '-closed' + ' ' + name + '-open' ).addClass( name + '-opening' );
                 // Change state
                 newState = 'true';

             }
             else {
                 // Define which action to take
                 action = 'deactivate';
                 // Add class to body
                 $body.removeClass( name + '-closed' + ' ' + name + '-open' + ' ' + name + '-opening' ).addClass( name + '-closing' );
                 // Change state
                 newState = 'false';
             }
             // Loop through each in group, updating aria-expanded
             for( var i = 0; i < $group.length; i++ ){
                 $group[i].attr( 'aria-expanded', newState );
             }
             // Loop through each, performing the action and attaching callback
             for( var j = 0; j < $toggles.length; j++ ){
                 $toggles[j].on( 'toggle:done', handleCompletion );
                 $toggles[j].trigger( 'toggle.mpress', [{ action : action }] );
             }
             return;
         };
         /**
          * Anonomous init function
          * Set initial values and organize execution of module
          * @type self invoking function to kickoff the module
          */
         ( function() {
             // Set some defaults
             name = $el.data( 'toggle' );
             // Perform some setup
             cacheDom();
             bindEvents();
         }());

         /**
          * @return (object) $el(ement) so other modules can work chain functions, as well as bind/unbind events
          */
         return $el;
    } // end button class

    /**
     * Attach module to Mpress global
     */
    Mpress.ToggleButtons = init();
})(jQuery, Mpress );