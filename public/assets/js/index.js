import axios from 'axios';

axios.defaults.withCredentials = true;

import '../bootstrap/css/bootstrap.css';
import '../scss/app.scss';

import retinajs from 'retinajs';
window.addEventListener( 'load', retinajs );

import 'simplebar';
import 'simplebar/dist/simplebar.min.css';

import jQuery from 'jquery';

( function( $ ) {

    if( ! window.$ ) {

        window.$ = $;
    }

    window.App = require( './app' );

    require( './vue/bootstrap' );

} )( jQuery )

