class Embed {

    constructor( root ) {

        this.options = require( '../embed-options.js' );

        this.$ = require( 'jquery' );

        this.root = root;

        


        this.$root = this.$( this.root );

        this.loadStylesheet();

        this.parseParams();

        this.initModal();

        this.scripLoaded = false;

        this.scriptLoadedPromise = this.load();

        this.$( document ).ready( () => this.bind() );

    }

    loadStylesheet() {

        if( this.options.stylesheet_url ) {

            this.$( 'head' ).append( '<link rel="stylesheet" href="' + this.options.stylesheet_url + '">' )
        }
    }

    parseParams() {

        this.params = {

            api_token: this.$root.data( 'partner' )

        };
    }

    initModal() {

        const tingle = require( 'tingle.js' );

        require( 'tingle.js/dist/tingle.min.css' );

        this.modal = new tingle.modal({

            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: [ 'moda-match-modal' ],
            onOpen: () => {

                this.track( 'App Opened', 'App', 'Opened', window.moda_match_options.partner_name )

                this.init();

            },
            onClose: () => {

                this.track( 'App Closed', 'App', 'Closed', window.moda_match_options.partner_name )


            }
        });

        this.modal.setContent( this.$root[ 0 ] );

        this.$root = this.$( this.modal.modalBoxContent ).find( '#moda-match-root' );

    }

    loadPromise(successCallback, failureCallback){
        const axios = require( 'axios' );
        axios.get( this.options.embed_url, {

            headers: {

                Authorization: 'Bearer ' + this.params.api_token
            }
        } )
            .then( ( response ) => {

                if( response ) {

                    if( response.data ) {

                        this.$root.html( response.data );
                        this.scripLoaded = true;
                    }
                }
                if(successCallback){
                    successCallback();
                }

            } )
            .catch( ( err ) => {

                this.$root.remove();
                if(failureCallback){
                    failureCallback();
                }

            } )
 
    }

    load() {
        if("Promise" in window){
            return new Promise((function(resolve, reject){
                return this.loadPromise(resolve, reject)
            }).bind(this));
        }else{
            return this.loadPromise();
        }
    }

    init() {

        if( this.inited ) {

            return;
        }

        this.inited = true;

        require('../../scss/app.scss');

        const Bootstrap = require( '../vue/bootstrap' );

        this.app = new Bootstrap( this, this.params.api_token );

    }

    bind() {

        this.$( '[data-moda-match]' ).click( ( e ) => {

            e.preventDefault();

            const trigger = this.$( e.delegateTarget );

            const productId = trigger.attr( 'data-moda-match' );
            if(this.scripLoaded){
                this.modal.open();
                this.app.setDeepLinkProduct( productId );
            }else{
                if(this.scriptLoadedPromise){
                    this.scriptLoadedPromise.then((function(){
                        this.modal.open();
                        this.app.setDeepLinkProduct( productId );
                    }).bind(this))
                }else{
                    console.error("Moda match is not fully initialized")
                }
            }

        } );

    }

    closeModal() {

        this.modal.close();
    }

    track( event, category, action, label, value ) {

        let payload = {
            Category: this.capEachWords( category ),
            Action: this.capEachWords( action )
        };

        if( label ) {

            payload.Label = this.capEachWords( label )
        }

        if( typeof value !== 'undefined' && value !== null ) {

            payload.Value = value;
        }

        analytics.track( this.capEachWords( event ), payload );
    }

    capFirstLetter( string ) {

        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    capEachWords( string ) {

        let words = string.split( ' ' );

        let cappedWords = [];

        for( let word of words ) {

            cappedWords.push( this.capFirstLetter( word ).trim() );
        }

        return cappedWords.join( ' ' );
    }

}

module.exports = Embed;
