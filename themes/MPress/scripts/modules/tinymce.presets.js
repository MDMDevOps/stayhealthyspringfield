jQuery( document ).ready( function( $ ) {

	var $Modal = new Controller();

    tinymce.create('tinymce.plugins.mpresspresets', {
        init : function( ed, url ) {
            ed.addButton('mpresspresets', {
                title : 'Insert Row',
                icon : 'mpress-icon-presets',
                onclick : function() {
                	$Modal.setEditor( ed );
                	$Modal.show();
                }
            });

        },
        createControl : function(n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add( 'mpress_presets', tinymce.plugins.mpresspresets );

    function Request() {
        var json, ajax;
        var send = function( postdata, callback ) {
        	ajax = $.post( mpressadmin.wpajaxurl, postdata );
        	ajax.done( function( response ) {
        	    try {
        	        json = JSON.parse( response );
        	    } catch( error ) {
        	        json = {
        	        	'error' : true,
        	        	'debug' : error,
        	        	'raw'     : response
        	        };
        	    } finally {
        	    	return callback( json );
        	    }
        	});
        };
        return {
        	send : send
        };
    }

    function Modal( $el, presets ) {

    	var request;
    	var cacheDom = function( callback ) {
    		// request.send( { action : 'get_theme_modal' }, function( response ) {
    		// 	if( response.error !== true ) {
    		// 		$el = $( response );
    				$el.hide().appendTo( 'body' );
    				$el.select = $el.find( 'select' );
    				$el.output = $el.find( 'textarea' );
    				$el.cancel = $el.find( '.cancel' );
    				$el.submit = $el.find( '#insert' );
    				$el.submit.on( 'click', submitValue );
    				$el.select.on( 'change', renderOutput );
    				$el.on( 'modal:down', down );
    				$el.on( 'modal:up', up );
    				$el.cancel.on( 'click', down );
    		// 	}
    		// });
    	};

    	var submitValue = function() {
    		$el.trigger( 'modal:select', [{ value : $el.output.val() }] );
    		$el.trigger( 'modal:down' );
    	};

    	var down = function() {
    		$el.fadeOut(300, function(){
    			reset();
    		});
    	};

    	var up = function() {
    		$el.fadeIn(300);
    	};

    	var reset = function() {
    		 $el.select.get(0).selectedIndex = 0;
    		 $el.select.trigger( 'change' );
    	};


    	var renderOutput = function( html ) {
    		var markup = presets[$el.select.val()].content;
    		$el.output.val( markup );
    	};

    	var getContent = function() {
    		return $el.output.val();
    	};

    	var getSubmit = function() {
    		return $el.submit;
    	};

    	var getModal = function() {
    		return $el;
    	};

    	var createForm = function() {
    		// console.log( Object.keys( presets ).length );
    	};


    	( function() {
    		cacheDom( function() {
    		});
    	})();
    	return $el;
    }

    function Controller() {
    	var request, presets, modal, value, editor;

    	var getPresets = function() {
    		request.send( { action : 'get_theme_presets_modal' }, function( response ) {
    			if( response.error !== true ) {
    				presets = response.presets;
    				modal   = new Modal( $( response.modal ), presets );
    				modal.on( 'modal:select', insert );

    			}
    		});
    	};

    	var setEditor = function(ed) {
    		editor = ed;
    	};

    	var insert = function( event, data ) {
    		value = data.value;
    		editor.selection.setContent( data.value );

    	};
    	var show = function() {
    		modal.trigger( 'modal:up' );
    	};

    	( function() {
    		request = new Request();
    		getPresets();
    	})();

    	return {
    		show : show,
    		setEditor : setEditor,
    	};
    }
});