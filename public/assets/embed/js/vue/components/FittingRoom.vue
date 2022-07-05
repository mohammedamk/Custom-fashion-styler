<template>

    <div class="moda-match-full-height">

        <div class="moda-match-split-view" style="height: 95%;">

            <div class="moda-match-split-view-wrapper">

              <div class="moda-match-split-view-left" v-if="isDesktop()">

                <product-options v-if="currentItem" :product="currentItem"></product-options>

              </div>

                <div class="moda-match-split-view-left">

                    <article class="moda-match-model-viewer" v-if="currentModel">

                        <figure class="moda-match-model-viewer-image">

                            <div v-show="currentSide === 'front'" v-html="currentModel.front_image.large"></div>
                            <div v-show="currentSide !== 'front'" v-html="currentModel.back_image.large"></div>

                            <div class="moda-match-model-viewer-overlays">

                                <div
                                    v-show="item.isActive"
                                    v-for="item in sortedItems"
                                    v-bind:key="item.id"
                                    v-html="getItemImageForViewer( item )"
                                >
                                </div>

                            </div>
                            <!-- /.moda-match-model-viewer-overlays -->

                        </figure>

                        <div class="moda-match-model-viewer-controls" v-if="isRotateEnabled || isTuckInEnabled">

                            <button
                                v-if="isRotateEnabled"
                                class="moda-match-model-viewer-controls-rotate"
                                :class="{'moda-match-model-viewer-controls-rotate--flip': currentSide !== 'front'}"
                                v-on:click.prevent="rotate()"
                                type="button">
                                <rotate-icon></rotate-icon>
                            </button>
                            <!-- /.moda-match-model-viewer-controls-rotate -->

                            <div class="moda-match-toggle" v-if="isTuckInEnabled">

                                <input
                                    id="input-tuck-in"
                                    type="checkbox"
                                    :value="true"
                                    v-model="tuckIn"
                                    name="model_gender"
                                >

                                <label for="input-tuck-in">Tuck In</label>

                            </div>
                            <!-- /.moda-match-toggle -->

                        </div>
                        <!-- /.moda-match-model-viewer-controls -->

                    </article>

                </div>
                <!-- /.moda-match-split-view-left -->

                <div class="moda-match-split-view-right">

                    <nav class="moda-match-vertical-items moda-match-vertical-items--4">

                        <article
                          class="moda-match-vertical-item moda-match-vertical-item--bg-white"
                          :class="{
                            'moda-match-vertical-item--active': item.isActive,
                            'moda-match-vertical-item--locked': item.isLocked
                          }"
                          v-for="item in items"
                          v-bind:key="item.id"
                          v-on:click.prevent="selectItem( item )"
                          v-if="item.image"
                        >

                          <figure class="moda-match-vertical-item-image"
                            v-if="item.activeVariant ? item.activeVariant.image : item.image"
                            v-html="item.activeVariant ? item.activeVariant.image.thumb : item.image.thumb"
                          >
                          </figure>

                        </article>

                    </nav>

                </div>
                <!-- /.moda-match-split-view-right -->

            </div>

        </div>

        <div class="moda-match-contained" v-if="isMobile()">

            <product-options v-if="currentItem" :product="currentItem"></product-options>

        </div>

        <div class="moda-match-powered-by">
            Powered by <a href="https://moda-match.com" rel="nofollow" target="_blank">Moda Match</a>
        </div>

    </div>
    <!-- /.moda-match-full-height -->

</template>

<script>

import $ from 'jquery';

import _ from 'lodash';

import ProductOptions from "./ProductOptions.vue";

import RotateIcon from '../../../images/svg/3d_rotate.svg?inline';
import LockIcon from '../../../images/svg/lock_black_24dp.svg?inline';

import options from "../../options";

export default {

    name: 'fitting-room',

    inject: [

        'getAsset',
        'getFittingRoomItems',
        'getFittingRoomItem',
        'getCurrentModel',
        'getDeepLinkProduct',
        'isMobile',
        'isDesktop',
        'track'
    ],

    components: {

        ProductOptions,
        RotateIcon,
        LockIcon
    },

    computed: {

        currentModel() {

            return this.getCurrentModel();
        },

        sortedItems() {

            let ordered = _.sortBy( this.items, ( o ) => {

                if( this.tuckIn ) {

                    if( o.layer === 2 ) {

                        return 1;
                    }
                    else if( o.layer === 1 ) {

                        return 2;
                    }
                }

                if( o.layer === 5 ) {

                    return 0;
                }

                return o.layer;

            } );

            return _.reverse( ordered );
        }
    },

    data() {

        return {

            headerTitle: 'Fitting Room',
            items: [],
            currentSide: 'front',
            currentItem: null,
            tuckIn: false,
            isRotateEnabled: options.is_rotate_enabled,
            isTuckInEnabled: options.is_tuck_in_enabled
        };
    },

    methods: {

        getItemImageForViewer( item ) {

            if( ! this.currentModel ) {

                return null;
            }

            let currentImage = _.find( item.activeVariant ? item.activeVariant.models : item.models, ( image ) => {

                return image.model_id === this.currentModel.id && image.side === this.currentSide;

            } );

            return currentImage ? currentImage.large : null;
        },

        filterTheOptions( the_item ) {

          if ( the_item ) {
            if ( the_item.hasOwnProperty( 'options' ) ) {

              let unused_options_arr = [];
              for ( var i = 1; i < 5; i++ ) {
                if ( the_item.options.hasOwnProperty( 'option' + i ) ) {
                  let currentItem_option = the_item.options[ 'option' + i ];
                  if ( this.currentModel && !this.findCommonElements3( this.currentModel.size, currentItem_option ) ) {
                    unused_options_arr.push( 'option' + i );
                  }
                }
              }

              the_item.unused_options_arr = unused_options_arr;

              return the_item;
            }
          }
        },

        selectItem( item ) {

            if( ! this.currentItem || this.currentItem.id !== item.id ) {
                this.currentItem = this.filterTheOptions( item );
            }

            let changes = this.predictLayersChange( item );

            if( this.checkForLayerLock( changes ) )
            {

                item.isActive = false;
                return;
            }

            if( item.isLocked ) {

                item.isActive = true;

                return;
            }

            item.isActive = !item.isActive;

            this.calculateLayers( item );

            if( item.isActive ) {

              this.currentItem = this.filterTheOptions( item );
            }
            else {

              let setCurrentItem = null;
              for (var i = 0; i < this.items.length; i++) {
                if ( this.items[i].isActive ) {
                  setCurrentItem = this.items[i];
                }
              }
              this.currentItem = this.filterTheOptions( setCurrentItem );
            }
        },

        lockItem( item ) {

            item.isLocked = ! item.isLocked;

            item.isActive = true;

            this.calculateLayers( item );
        },

        calculateLayers( item ) {

            if( item.isActive && item.layer ) {

                this._layeringDisableOtherItemsFromLayer( item.layer, item.id );

                switch( item.layer ) {

                    case 5:

                        this._layeringDisableLayers( [ 4 ] );

                        break;
                    case 4:

                        this._layeringDisableLayers( [ 1, 2, 3, 5 ] );

                        break;
                    case 3:

                        this._layeringDisableLayers( [ 4 ] );

                        break;
                    case 2:


                        this._layeringDisableLayers( [ 4 ] );

                        break;
                    case 1:


                        this._layeringDisableLayers( [ 4 ] );

                        break;
                }
            }
        },

        predictLayersChange( item ) {

            let changes = [];

            if( item.layer ) {

                let categoryChanges = this._layeringDisableOtherItemsFromLayer( item.layer, item.id, true )

                for( let change of categoryChanges ) {

                    changes.push( change );
                }

                let otherChanges = []

                switch( item.layer ) {

                    case 5:

                        otherChanges = this._layeringDisableLayers( [ 4 ], true );

                        break;
                    case 4:

                        otherChanges = this._layeringDisableLayers( [ 1, 2, 3, 5 ], true );

                        break;
                    case 3:

                        otherChanges = this._layeringDisableLayers( [ 4 ], true );

                        break;
                    case 2:


                        otherChanges = this._layeringDisableLayers( [ 4 ], true );

                        break;
                    case 1:


                        otherChanges = this._layeringDisableLayers( [ 4 ], true );

                        break;
                }

                for( let change of otherChanges ) {

                    changes.push( change );
                }
            }

            return changes;
        },

        checkForLayerLock( items ) {

            return _.filter( items, ( i ) => {

                return i.isLocked;

            } ).length > 0;
        },

        preloadItems() {

            for( let item of this.items ) {

                $( this.$parent.$refs.preload ).append( this.getItemImageForViewer( item ) );
            }
        },

        retrieveItems() {

            this.items = this.getFittingRoomItems();

            let active = null;

            for( let item of this.items ) {

                if( item.isActive ) {

                    if( ! active ) {

                        active = item;
                    }

                    this.calculateLayers( item );
                }
            }

            if( active ) {

                this.currentItem = this.filterTheOptions( active );
            }

            this.detectDeepLink();

            this.preloadItems();

            this.track( 'Fitting Viewed', 'Fitting', 'Viewed', null, this.items.length );
        },

        _layeringDisableOtherItemsFromLayer( layer, exclude, returnOnly ) {

            if( ! Array.isArray( exclude ) ) {

                exclude = [
                    exclude
                ];
            }

            let changes = [];

            for( let item of this.$parent.fittingRoomItems ) {

                if( exclude.indexOf( item.id ) > -1 ) {

                    continue;
                }

                if( item.layer !== layer ) {

                    continue;
                }

                if( ! returnOnly ) {

                    item.isActive = false;
                }
                else {

                    if( item.isActive ) {

                        changes.push( item );
                    }
                }
            }

            return changes;
        },

        _layeringDisableLayers( layers, returnOnly ) {

            let changes = [];

            for( let item of this.$parent.fittingRoomItems ) {

                if( layers.indexOf( item.layer ) > -1 ) {

                    if( ! returnOnly ) {

                        item.isActive = false;
                    }
                    else {

                        if( item.isActive ) {

                            changes.push( item );
                        }
                    }
                }
            }

            return changes;
        },

        rotate() {

            if( ! this.isRotateEnabled ) {

                if( this.currentSide !== 'front' ) {

                    this.currentSide = 'front';
                }

                return;
            }

            this.track( 'Rotate Selected', 'Rotate', 'Selected' );

            if( this.currentSide === 'front' ) {

                this.currentSide = 'back';
            }
            else {

                this.currentSide = 'front';
            }

            this.track( 'Rotate Result', 'Rotate', 'Result', null, this.currentSide !== 'front' ? 1 : 0 );
        },

        detectDeepLink() {

            const deepLinkProduct = this.getDeepLinkProduct();

            if( deepLinkProduct ) {

                const product = this.getFittingRoomItem( deepLinkProduct );

                if( product ) {

                    product.isActive = true;

                    this.calculateLayers( product );

                    this.currentItem = this.filterTheOptions( product );
                }
            }
        },


        findCommonElements3(arr1, arr2) {
          return arr1.some(item => arr2.includes(item))
        }
    },

    watch: {

        currentSide: function( side ) {

            this.preloadItems();
        },

        '$parent.deepLinkProduct': function( deepLinkProduct ) {

            this.detectDeepLink();
        },

        tuckIn: function() {

            this.track( 'Tuck In Selected', 'Tuck In', 'Selected' );
            this.track( 'Tuck In Result', 'Tuck In', 'Result', null, this.tuckIn ? 1 : 0 );

        },

        currentItem: function() {

        }
    },

    created() {

      this.retrieveItems();

      if ( !this.items.length && !this.currentModel ) {
        alert( "Please select a model and an item first!" );
        this.$router.replace( '/' );
      } else if( !this.items.length ) {
        alert( "Please select an item first!" );
        this.$router.replace( '/' );
      } else if ( !this.currentModel ) {
        if(this.$router.currentRoute && this.$router.currentRoute.query && (this.$router.currentRoute.query.openFromProductPage === "true" || this.$router.currentRoute.query.openFromProductPage === true)){
            alert( "To try-on this item, please select a model" );
        }else{
            alert( "Please select a model first" );
        }

        this.$router.replace( '/models' );
      }

    }

};

</script>
