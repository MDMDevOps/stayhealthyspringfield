// @codekit prepend "vendor/jquery.sumoselect.js"
jQuery( document ).ready(function($) {
    'use strict';
    $('#widgets-right .SlectBox').SumoSelect();
});
// Ajax success function
jQuery( document ).ajaxSuccess( function( e, xhr, settings ) {
    if( typeof settings.data === 'undefined' ) {
        return;
    } else {
        if( settings.data.search( 'action=save-widget') != -1 ) {
            jQuery('#widgets-right .SlectBox').SumoSelect();
        }
    }
});