import options from './options';

import Vue from 'vue';

let app = null;

class Binding {

    constructor() {

        this.options = options;

    }

    makeRequest( url, data, method, options ) {

        return new Promise( ( resolve, reject ) => {

            method = method || 'post';

            method = method.toLowerCase();

            let ajaxOptions = {
                url: url,
                dataType: 'json',
                method: method,
                data: data
            };

            if( method !== 'post' && method !== 'get' ) {
                if( ajaxOptions.data instanceof FormData ) {
                    ajaxOptions.data.set( '_method', method );
                }
                else {
                    if( ! ajaxOptions.data ) {
                        ajaxOptions.data = {};
                    }
                    ajaxOptions.data._method = method;
                }
                ajaxOptions.method = 'post';
            }

            if( ajaxOptions.method === 'post' && ajaxOptions.data instanceof FormData ) {
                ajaxOptions.processData = false;
                ajaxOptions.contentType = false;
            }

            if( options ) {

                ajaxOptions = $.extend( ajaxOptions, options );
            }

            $.ajax( ajaxOptions )
                .done( ( response ) => {

                    resolve( response );

                } )
                .fail( ( xhr ) => {

                    reject( xhr.responseJSON );

                } );

        } );

    }

    processAPIResponse( response, form ) {

        if( ! response ) {

            return;
        }

        if( response.redirect ) {

            Vue.notify( {
                group: 'global',
                title: response.message,
                text: 'Redirecting...',
                type: 'info'
            } );
            document.location.assign( response.redirect );
            return;
        }


        Vue.notify( {
            group: 'global',
            type: 'success',
            text: response.message ? response.message : 'Your changes have been saved.'
        } );

    }

    processAPIError( container, response ) {

        container = $( container );

        if( response && response.message ) {

            Vue.notify( {
                group: 'global',
                type: 'error',
                text: response.message
            } );
        }

        Bus.$nextTick( () => {

            this.scrollTo( container.find( '.has-error' ).first() );

        } );
    }

}

app = new Binding();

module.exports = app;
