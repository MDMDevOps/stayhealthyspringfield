( function( $, Mpress ) {
    'use strict';
    var lkasjdf;
    // console.log( Mpress );
    var experimentalMod = function() {
        console.log( 'Callable function' );
    };

    var kickoff = function() {
            lkasjdf = 'My new module';
            return {
                exp : experimentalMod
            };
    };
    Mpress.experiment = kickoff();
    console.log( Mpress );
    // console.log( Mpress );
})(jQuery, Mpress );