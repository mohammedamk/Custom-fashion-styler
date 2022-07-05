<template>

    <div id="moda-match-app" :class="{'moda-match-app--no-scroll': hasActiveModals || preventScroll}">

        <app-header>
            {{headerTitle}}
        </app-header>

        <notifications group="global" />

        <div class="moda-match-app-inner">

            <transition name="fade" mode="out-in">
                <router-view ref="currentView"></router-view>
            </transition>

        </div>
        <!-- /.moda-match-app-inner -->

        <app-footer></app-footer>

        <modal ref="productModal">

            <div v-if="deepLinkProduct">

                <figure v-if="deepLinkProduct.activeVariant ? deepLinkProduct.activeVariant.image : deepLinkProduct.image" class="moda-match-modal-image" v-html="deepLinkProduct.activeVariant ? deepLinkProduct.activeVariant.image.thumb : deepLinkProduct.image.thumb"></figure>
                <!-- /.moda-match-modal-image -->

                <div class="moda-match-modal-price">{{formatPrice( deepLinkProduct.activeVariant ? deepLinkProduct.activeVariant.price : deepLinkProduct.price )}}</div>
                <!-- /.moda-match-modal-price -->

                <div class="moda-match-modal-text" v-html="deepLinkProduct.activeVariant ? deepLinkProduct.activeVariant.description : deepLinkProduct.description"></div>
                <!-- /.moda-match-modal-text -->

                <footer class="moda-match-modal-footer">

                    <button
                        class="moda-match-btn moda-match-btn--block moda-match-btn--size-medium"
                        :class="{'moda-match-btn--secondary': hasFittingRoomItem( deepLinkProduct )}"
                        type="button"
                        v-on:click.prevent="! hasFittingRoomItem( deepLinkProduct ) ? addFittingRoomItem( deepLinkProduct, true ) : removeFittingRoomItem( deepLinkProduct )"
                    >
                        {{ ! hasFittingRoomItem( deepLinkProduct ) ? 'Add to fitting room' : 'Remove from fitting room' }}
                    </button>
                    <!-- /.moda-match-btn modal-match-btn--block -->

                </footer>
                <!-- /.moda-match-modal-footer -->

            </div>

        </modal>

        <div class="moda-match-preloaded" ref="preload"></div>
        <!-- /.moda-match-preloaded -->

    </div>
    <!-- /#moda-match-app -->

</template>

<script>

import options from '../../options';

import AppHeader from "./AppHeader.vue";
import AppFooter from "./AppFooter.vue";

import _ from 'lodash';

import axios from 'axios';

import Modal from './Modal.vue';

import $ from 'jquery';

export default {

    name: 'app',

    props: [],

    inject: [

        'getApiToken',
        'track'
    ],

    components: {

        AppHeader,
        AppFooter,
        Modal
    },

    computed: {

        hasActiveModals() {

            let active = false;

            for( let modal of this.registeredModals ) {

                if( modal.isOpen ) {

                    active = true;
                    break;
                }
            }

            return active;
        },

        sessionPrefix() {

            const token = this.getApiToken();

            const tokenParts = token.split( '|' );

            return 'modaMatchEmbed' + tokenParts[ 0 ];
        },
    },

    data() {
        return {
            headerTitle: this.$route && this.$route.meta && this.$route.meta.title ? this.$route.meta.title : 'Moda Match',
            transitionName: 'slide-left',
            currentModel: null,
            fittingRoomItems: [],
            cartItems: [],
            registeredModals: [],
            preventScroll: false,
            deepLinkProduct: null,
            $viewport: null,
            currentViewportWidth: 0,
            collections: [],
            products: [],
            models: []
        };
    },

    methods: {

        getAsset( path ) {

            if( path.charAt( 0 ) !== '/' ) {

                path = '/' + path;
            }

            return options.assets_url + path;
        },

        getUrl( path ) {

            if( path.charAt( 0 ) !== '/' ) {

                path = '/' + path;
            }

            return options.base_url + path;
        },

        formatPrice( price ) {

            return 'CA$' + price.toFixed( 2 );
        },

        setActiveModel( model ) {

            this.track( 'Model Selected', 'Model', 'Selected', model.name );

            this.currentModel = model;
            this.changeAllFittingRoomItemsActiveVariantToFitModel(model);
        },

        hasFittingRoomItem( item ) {

            return !! this.getFittingRoomItem( item );
        },

        getFittingRoomItem( item ) {

            return _.find( this.fittingRoomItems, ( i ) => {

                return i.id === item.id;

            } );
        },

        getFittingRoomItems() {

            return this.fittingRoomItems;
        },

        addFittingRoomItem( item, redirect ) {

            if( ! this.hasFittingRoomItem( item ) ) {

                item.isAdded = true;

                this.fittingRoomItems.push( item );

                this.track( "Product Added", 'Product', 'Added', item.title );

                if(this.getCurrentModel()){
                    this.changeFittingRoomItemsActiveVariantToFitModel (this.getCurrentModel(), item);
                }

                if( redirect ) {

                    if( this.$refs.productModal ) {

                        this.$refs.productModal.close();
                    }

                    if( this.$router.currentRoute.name !== 'fitting-room' ) {

                        this.$router.push( '/fitting-room' );
                    }
                }

                this.$notify( {

                    group: 'global',
                    title: 'Item added to fitting room.',
                    type: 'success'

                } );
            }
        },

        changeAllFittingRoomItemsActiveVariantToFitModel (model) {
            if(model && this.fittingRoomItems && model.size){
                loopThroughFittingRoomItems:
                for( let fittingRoomItem of this.fittingRoomItems){
                    this.changeFittingRoomItemsActiveVariantToFitModel(model, fittingRoomItem);
                }
            }
        },

        changeFittingRoomItemsActiveVariantToFitModel (model, fittingRoomItem){
            if(model && fittingRoomItem && model.size){
                loopThroughVariants:
                for(let variant of fittingRoomItem.variants){
                    loopThroughVariantOptions:
                    for(let variantOption in variant){
                        if(variantOption.startsWith("option") && variant && variant[variantOption] && model.size.includes(variant[variantOption])){
                            //matching variant with size option was found matching model size
                            this.setActiveVariantForItem( fittingRoomItem, variant );
                            break loopThroughVariants;
                        }
                    }
                }

            }
        },

        removeFittingRoomItem( item ) {

            let foundItem = this.getFittingRoomItem( item );

            if( foundItem ) {

                foundItem.isAdded = false;

                this.fittingRoomItems = _.without( this.fittingRoomItems, foundItem );

                this.track( "Product Removed", 'Product', 'Removed', item.title );

                this.$notify( {

                    group: 'global',
                    title: 'Item removed from fitting room.',
                    type: 'error'

                } );
            }
        },

        setActiveVariantForItem( item, variant ) {

            if( ! variant ) {

                if( typeof item.variants !== 'undefined' ) {

                    for( let _variant of item.variants ) {

                        variant = _variant;
                        break;
                    }
                }
            }

            let fittingRoomItem = this.getFittingRoomItem( item );

            if( fittingRoomItem ) {

                fittingRoomItem.activeVariant = variant;
            }
        },

        getCurrentModel() {

            return this.currentModel;
        },

        registerModal( modal ) {

            this.registeredModals.push( modal );
        },

        closeActiveModals() {

            for( let modal of this.registeredModals ) {

                if( modal.isOpen ) {

                    modal.close();
                }
            }
        },

        hasCartItem( item ) {

            return !! this.getCartItem( item );
        },

        getCartItem( item ) {

            return _.find( this.cartItems, ( i ) => {

                return i.id === item.id;

            } );
        },

        getCartItems() {

            return this.cartItems;
        },

        addCartItem( item ) {

            if( ! this.hasCartItem( item ) ) {

                this.cartItems.push( item );

                this.track( "Product Added To Cart", 'Product', 'Added', item.title );
            }
        },

        updateCartItem( item, action ) {

            if( this.hasCartItem( item ) ) {

              this.removeCartItem( item );

              if ( action === 'pls' ) {
                ++item.quantity;
              } else if ( action === 'mns' ) {
                --item.quantity;
              }

              if ( item.quantity > 0 ) {
                this.addCartItem( item );
              }

              this.track( "Product Updated To Cart", 'Product', 'Updated', item.title );
            }

            return this.cartItems;
        },

        removeCartItem( item ) {

            let foundItem = this.getCartItem( item );

            if( foundItem ) {

                this.cartItems = _.without( this.cartItems, foundItem );

                this.track( "Product Removed From Cart", 'Product', 'Removed', item.title );
            }

            return this.cartItems;
        },

        reloadState() {

            if( ! window.sessionStorage ) {

                return;
            }

            let cartItems = this.getFromSession( 'cartItems' );

            if( cartItems ) {

                this.cartItems = cartItems;

                for( let loopItem of this.cartItems ) {

                    ( ( item ) => {

                        this.getProductFromId( item.id )
                            .then( ( refreshedItem ) => {

                                Object.assign( item, this.createProductObject( refreshedItem, item ) );

                            } )
                            .catch( ( err ) => {

                                this.removeCartItem( item );

                            } );

                    } )( loopItem );
                }
            }

            let fittingRoomItems = this.getFromSession( 'fittingRoomItems' );

            if( fittingRoomItems ) {

                this.fittingRoomItems = fittingRoomItems;

                for( let loopItem of this.fittingRoomItems ) {

                    ( ( item ) => {

                        this.getProductFromId( item.id )
                            .then( ( refreshedItem ) => {

                                Object.assign( item, this.createProductObject( refreshedItem, item ) );

                            } )
                            .catch( ( err ) => {

                                this.removeFittingRoomItem( item );

                            } );

                    } )( loopItem );
                }
            }

            let model = this.getFromSession( 'currentModel' );

            if( model ) {

                this.currentModel = model;

                this.getModelFromId( model.id )
                    .then( ( refreshedModel ) => {

                        this.currentModel = refreshedModel;

                    } )
                    .catch( ( err ) => {

                        this.currentModel = null;

                    } )
            }
        },

        preventAppScroll( prevent ) {

            this.preventScroll = prevent;
        },

        saveToSession( key, data ) {

            if( window.sessionStorage ) {

                window.sessionStorage.setItem( this.sessionPrefix + '_' + key, JSON.stringify( data ) );
            }
        },

        getFromSession( key ) {

            if( window.sessionStorage ) {

                let data = window.sessionStorage.getItem( this.sessionPrefix + '_' + key );

                if( data ) {

                    return JSON.parse( data );
                }
            }

            return null;
        },

        getProductFromId( id ) {

            return new Promise( ( resolve, reject ) => {

                axios.get( options.api_url + 'products/' + id, {

                    headers: {

                        Authorization: 'Bearer ' + this.getApiToken()

                    }
                } )
                .then( ( response ) => {

                    if( response ) {

                        if( response.data ) {

                            response = response.data;
                        }

                        if( response.product ) {

                            resolve( response.product )

                            return;
                        }
                    }

                    reject();

                } )
                .catch( ( err ) => {

                    reject( err );

                } );

            } );
        },

        getProductFromSourceId( id ) {

            return new Promise( ( resolve, reject ) => {

                axios.get( options.api_url + 'products/from-source/' + id, {

                    headers: {

                        Authorization: 'Bearer ' + this.getApiToken()

                    }
                } )
                    .then( ( response ) => {

                        if( response ) {

                            if( response.data ) {

                                response = response.data;
                            }

                            if( response.product ) {

                                let productWithVariant = response.product;

                                if( response.variant ) {

                                    productWithVariant.activeVariant = response.variant;
                                }

                                resolve( this.createProductObject( response.product, productWithVariant ) )

                                return;
                            }
                        }

                        reject();

                    } )
                    .catch( ( err ) => {

                        reject( err );

                    } );

            } );
        },

        getModelFromId( id ) {

            return new Promise( ( resolve, reject ) => {

                axios.get( options.api_url + 'models/' + id, {

                    headers: {

                        Authorization: 'Bearer ' + this.getApiToken()

                    }
                } )
                    .then( ( response ) => {

                        if( response ) {

                            if( response.data ) {

                                response = response.data;
                            }

                            if( response.model ) {

                                resolve( response.model )

                                return;
                            }
                        }

                        reject();

                    } )
                    .catch( ( err ) => {

                        reject( err );

                    } );

            } );
        },

        createProductObject( item, previous ) {

            let isActive = false;
            let isAdded = false;
            let isLocked = false;

            if( item.id ) {

                let addedItem = this.getFittingRoomItem( item );

                if( addedItem ) {

                    isAdded = true;
                    isActive = addedItem.isActive;
                }
            }

            let activeVariant = null;

            if( typeof item.variants !== 'undefined' ) {

                for( let variant of item.variants ) {

                    activeVariant = variant;
                    break;
                }
            }

            if( previous ) {

                isActive = previous.isActive;
                isAdded = previous.isAdded;
                isLocked = previous.isLocked;

                if( previous.activeVariant && typeof item.variants !== 'undefined' ) {

                    activeVariant = _.find( item.variants, ( v ) => {

                        return v.id === previous.activeVariant.id;

                    } );
                }
            }

            return _.extend( {

                id: null,
                title: '',
                price: null,
                description: null,
                image: {},
                models: [],
                isActive: isActive,
                isAdded: isAdded,
                isLocked: isLocked,
                variants: [],
                activeVariant: activeVariant

            }, item );
        },

        setDeepLinkProduct( id ) {

            this.getProductFromSourceId( id )
            .then( ( product ) => {

                this.deepLinkProduct = product;

                if( ! this.hasFittingRoomItem( product ) ) {

                    this.addFittingRoomItem( product );
                }
                else {

                    this.$notify( {

                        group: 'global',
                        title: 'Item added to fitting room.',
                        type: 'success'

                    } );
                }

                if( this.$router.currentRoute.name !== 'fitting-room' ) {

                    this.$router.push( '/fitting-room?openFromProductPage=true' );
                }
            } )
            .catch( (err) => {


            } );
        },

        getDeepLinkProduct() {

            return this.deepLinkProduct;
        },

        isMobile() {

            return this.currentViewportWidth < 800;
        },

        isDesktop() {

            return this.currentViewportWidth >= 800;
        },

        detectViewport() {

            this.currentViewportWidth = this.$viewport.width();
        },

        bindViewportResize() {

            $( window ).resize( () => {

                this.detectViewport();

            } );

            this.detectViewport();
        },

        retrieveTheCollections() {
          return this.collections;
        },

        setTheCollections( collections ) {
          this.collections = collections;
        },

        retrieveTheProducts() {
          return this.products;
        },

        setTheProducts( products ) {
          this.products = products;
        },

        retrieveTheModels() {
          return this.models;
        },

        setTheModels( models ) {
          this.models = models
        }

    },

    provide() {

        return {

            getAsset: this.getAsset,
            getUrl: this.getUrl,
            formatPrice: this.formatPrice,
            setActiveModel: this.setActiveModel,
            addFittingRoomItem: this.addFittingRoomItem,
            removeFittingRoomItem: this.removeFittingRoomItem,
            hasFittingRoomItem: this.hasFittingRoomItem,
            getFittingRoomItem: this.getFittingRoomItem,
            getFittingRoomItems: this.getFittingRoomItems,
            getCurrentModel: this.getCurrentModel,
            registerModal: this.registerModal,
            closeActiveModals: this.closeActiveModals,
            setActiveVariantForItem: this.setActiveVariantForItem,
            addCartItem: this.addCartItem,
            updateCartItem: this.updateCartItem,
            removeCartItem: this.removeCartItem,
            hasCartItem: this.hasCartItem,
            getCartItem: this.getCartItem,
            getCartItems: this.getCartItems,
            preventAppScroll: this.preventAppScroll,
            createProductObject: this.createProductObject,
            getDeepLinkProduct: this.getDeepLinkProduct,
            isMobile: this.isMobile,
            isDesktop: this.isDesktop,
            retrieveTheCollections: this.retrieveTheCollections,
            setTheCollections: this.setTheCollections,
            retrieveTheProducts: this.retrieveTheProducts,
            setTheProducts: this.setTheProducts,
            retrieveTheModels: this.retrieveTheModels,
            setTheModels: this.setTheModels
        };
    },

    watch: {

        '$route' (to, from) {

            const toDepth = to.path.split('/').length
            const fromDepth = from.path.split('/').length
            this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left';

            if( to.meta && to.meta.title ) {

                this.headerTitle = to.meta.title;
            }
            else {

                this.headerTitle = 'Moda Match';
            }

        },

        'cartItems': {

            deep: true,
            handler: function( items ) {

                if( window.sessionStorage ) {

                    this.saveToSession( 'cartItems', items );
                }
            }
        },

        'fittingRoomItems': {

            deep: true,
            handler: function( items ) {

                if( window.sessionStorage ) {

                    this.saveToSession( 'fittingRoomItems', items );
                }
            }
        },

        'currentModel': function( model ) {

            if( window.sessionStorage ) {

                this.saveToSession( 'currentModel', model );
            }
        }
    },

    created() {

        this.reloadState();

    },

    mounted() {

        this.$viewport = $( window.ModaMatch.$refs.app.$el );

        this.bindViewportResize();

    }

};


</script>
