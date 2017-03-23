jQuery( function( $ ) {
	'use strict';

	var terminus, $wrapper;

	( function() {
		var cacheTerminus = $( '.mdm_devtools #terminus' );
		if( cacheTerminus.length ) {
			terminus = new Terminus( cacheTerminus );
		}
	})();

	/**
	 * Request Class
	 * Used to get results via ajax
	 * @param (json) postdata : Request data to send
	 * @param (function) callback : Function to run after data has been retrieved
	 */
	function Request() {
	    var json, ajax;
	    var send = function( postdata, callback ) {
	    	ajax = $.post( mdm_devtools.wpajaxurl, postdata );
	    	ajax.done( function( response ) {
	    	    try {
	    	        json = JSON.parse( response );
	    	        return callback( json );
	    	    } catch( error ) {
	    	        json = {
	    	        	'error' : true,
	    	        	'message' : error,
	    	        	'raw'     : response
	    	        };
	    	        return callback( json );
	    	    }
	    	});
	    };
	    return {
	    	send : send
	    };
	}

	function Terminus( $el ) {
		var request, $terminal, $select, $submit;

		var cacheDom = function() {
			$terminal = $el.find( '.terminal' );
			$select   = $el.find( 'select' );
			$submit   = $el.find( 'button' );
		};

		var bindEvents = function() {
			$submit.on( 'click', getResult );
		};

		var getResult = function( event ) {
			event.preventDefault();
			var state = $select.val();
			if( confirmRequest( state ) === true ) {
				render( 'Running, Please Wait...<span class="blinking-cursor">|</span>' );
				request.send( { action : 'git_terminus', state : $select.val() }, function( response ) {
					console.log(response);
					if( response.error ) {
						render( response.raw );
					} else {
						render( response );
					}
				});
			}
		};

		var confirmRequest = function( state ) {
			var confirmation;
			if( state === 'reset' ) {
				confirmation = confirm( 'Reset Hard will wipe out anything locally, and reset your local repo to the current state of origin/master. If you have any changes on this machine that dont exist in origin, they will be lost. Use with caution. Continue?' );
			} else {
				confirmation = true;
			}
			return confirmation;
		};

		var render = function( html ) {
			$terminal.html( html ).animate( { scrollTop: $terminal.get(0).scrollHeight }, "slow" );
		};

		(function() {
			request = new Request();
			cacheDom();
			bindEvents();
		})();
	}
}); // end document ready
