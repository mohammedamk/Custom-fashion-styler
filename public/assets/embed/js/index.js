( function() {

    let root = document.querySelector( '#moda-match-root' );

    if( ! root || root.length < 1 ) {

        return;
    }

    const Embed = require( './embed/Embed' );

    new Embed( root );

} )();
