<template>

    <div class="moda-match-full-height">

        <div v-if="!isLoading" class="moda-match-margin--b-16 moda-match-contained moda-match-btn--sticky-top">
            <!-- /.moda-match-btn moda-match-btn--gray moda-match-btn--size-extra-small -->

            <!-- <div class="moda-match-contained" v-if="! isLoading"> -->

                <button v-if="activeModel" class="moda-match-btn moda-match-btn--pink moda-match-btn--block moda-match-btn--size-small" v-on:click="confirmModel()">
                  GO TO FITTING ROOM
                </button>
                <!-- /.btn btn--brand -->

            <!-- </div> -->
            <!-- /.moda-match-contained -->

        </div>

        <div class="moda-match-split-view" v-if="! isLoading">

            <div class="moda-match-split-view-wrapper">

                <div class="moda-match-split-view-left">

                    <article class="moda-match-model-viewer" v-if="activeModel">

                        <header class="moda-match-model-viewer-header">

                            <h1 class="moda-match-model-viewer-name">
                                {{activeModel.name}}
                            </h1>
                            <!-- /.moda-match-model-viewer-name -->

                            <!-- <div class="moda-match-model-viewer-size" v-if="activeModel.size">
                                Size {{activeModel.size.join( '/' )}}
                            </div> -->
                            <!-- /.moda-match-model-viewer-size -->

                            <div class="moda-match-model-viewer-size show-on-desktop" v-if="activeModel.dimensions && activeModel.dimensions.height">
                                Height: {{getFeetAndInchesStringFromCm(activeModel.dimensions.height)}}
                            </div>
                            <div class="moda-match-model-viewer-size show-on-desktop no-delimiter" v-if="activeModel.dimensions && activeModel.dimensions.weight">
                                Weight: {{activeModel.dimensions.weight}} lbs
                            </div>

                            <div class="moda-match-model-viewer-size show-on-mobile" v-if="activeModel.dimensions && activeModel.dimensions.height">
                                H: {{getFeetAndInchesStringFromCm(activeModel.dimensions.height)}}
                            </div>
                            <div class="moda-match-model-viewer-size show-on-mobile no-delimiter" v-if="activeModel.dimensions && activeModel.dimensions.weight">
                                W: {{activeModel.dimensions.weight}} lbs
                            </div>


                            <div class="moda-match-model-viewer-controls" v-if="isRotateEnabled">
                              <button
                                class="moda-match-model-viewer-sizes-toggle"
                                type="button"
                                v-on:click="toggleSizesDisplay()"
                                :class="{'moda-match-model-viewer-sizes-toggle--is-active': showSizes}"
                                ref="modaMatchModelViewerSizesToggle"
                              >
                                <measuring-tape-icon></measuring-tape-icon>

                              </button>
                              <!-- /.moda-match-model-viewer-sizes-toggle -->

                              <button
                                class="moda-match-model-viewer-controls-rotate"
                                :class="{'moda-match-model-viewer-controls-rotate--flip': currentSide !== 'front'}"
                                v-on:click.prevent="rotate()"
                                type="button"
                              >
                                <rotate-icon></rotate-icon>
                              </button>
                              <!-- /.moda-match-model-viewer-controls-rotate -->

                            </div>
                            <!-- /.moda-match-model-viewer-controls -->


                        </header>
                        <!-- /.moda-match-model-viewer-header -->

                        <figure
                            class="moda-match-model-viewer-image"
                            ref="modelImageContainer"
                            :class="{'moda-match-model-viewer-image--zoomed-out': showSizes}"
                        >

                            <div v-show="currentSide === 'front'" v-html="activeModel.front_image.large"></div>
                            <div v-show="currentSide !== 'front'" v-html="activeModel.back_image.large"></div>

                        </figure>
                        <!-- /.moda-match-model-viewer-image -->

                        <transition name="fade" mode="out-in">

                            <div
                                class="moda-match-model-viewer-sizes"
                                v-if="modelImageLoadingCompleted && showSizes"
                                :style="{
                                    height: modelImageHeight ? ( modelImageHeight + 'px' ) : null,
                                    width: modelImageWidth ? ( modelImageWidth + 'px' ) : null
                                }"
                            >

                                <div
                                    class="moda-match-model-viewer-sizes-marker"
                                    v-for="(size, sizeType) in activeModel.dimensions"
                                    :style="getSizePosition(sizeType)"
                                    v-if="getSizePosition(sizeType)"
                                    :class="{'moda-match-model-viewer-sizes-marker--reversed': getSizePosition(sizeType).left}"
                                >

                                    <span
                                        :style="{width: modelImageWidth ? ( ( modelImageWidth * 0.12 ) + 'px' ) : null}"
                                    ></span>
                                    <strong>{{getSizeLabel(sizeType)}} <br> {{size + ( sizeType !== 'bust' ? ' in' : '' ) }}</strong>

                                </div>
                                <!-- /.moda-match-model-viewer-sizes-marker -->

                            </div>
                            <!-- /.moda-match-model-viewer-sizes -->

                        </transition>



                    </article>
                    <!-- /.moda-match-model-viewer -->


                </div>
                <!-- /.moda-match-split-view-left -->

                <div class="moda-match-split-view-right">

                    <nav class="moda-match-vertical-items moda-match-vertical-items--4">

                      <article
                        class="moda-match-vertical-item"
                        :class="{'moda-match-vertical-item--active': activeModel && model.id === activeModel.id}"
                        v-for="model in modelsToDisplay"
                        v-on:click.prevent="selectModel( model )"
                      >

                        <span class="moda-match-vertical-item-badge" v-if="activeModel && model.id === activeModel.id"></span>
                        <!-- /.moda-match-vertical-item-badge -->

                        <figure class="moda-match-vertical-item-image" v-html="model.profile_image.thumb">

                        </figure>
                        <!-- /.moda-match-vertical-item-image -->

                        <header class="moda-match-vertical-item-header">

                          <h1 class="moda-match-vertical-item-name">
                            {{model.name}}
                          </h1>
                          <!-- /.moda-match-vertical-item-name -->

                          <div class="moda-match-vertical-item-size" v-if="model.size">
                            Size {{model.size.join( '/' )}}
                          </div>
                          <!-- /.moda-match-vertical-item-size -->

                        </header>
                        <!-- /.moda-match-vertical-item-header -->

                      </article>
                      <!-- /.moda-match-vertical-item -->

                    </nav>
                    <!-- /.moda-match-vertical-items -->

                </div>
                <!-- /.moda-match-split-view-right -->

            </div>
            <!-- /.moda-match-split-view-wrapper -->

        </div>
        <!-- /.moda-match-models -->

        <div v-if="! isLoading" class="moda-match-margin--b-30 moda-match-contained">

            <p class="moda-match-text--type-1 moda-match-margin--b-6">Not sure which one is right for you? We can help!</p>
            <!-- /.moda-match-text--type-1 -->

            <button
                v-if="! filteredModels || filteredModels.length < 1"
                class="moda-match-btn moda-match-btn--gray moda-match-btn--size-extra-small moda-match-btn--block moda-match-btn--color-black moda-match-btn--border-black"
                v-on:click="openNaturalSearch( 'Enter your measurements' )">
                Enter your measurements
            </button>
            <!-- /.moda-match-btn moda-match-btn--gray moda-match-btn--size-extra-small -->

            <!-- <button
              v-if="activeModel"
              class="moda-match-btn moda-match-btn--pink moda-match-btn--block moda-match-margin--t-4 moda-match-btn--size-small"
              v-on:click="confirmModel()"
            >
              GO TO FITTING ROOM
            </button> -->

            <button
                v-else
                class="moda-match-btn moda-match-btn--secondary moda-match-btn--size-extra-small moda-match-btn--block "
                v-on:click="resetFilters()">
                Show all models
            </button>
            <!-- /.moda-match-btn moda-match-btn--gray moda-match-btn--size-extra-small -->



        </div>


        <loader v-else></loader>

        <natural-search ref="naturalSearch" v-if="! isLoading"></natural-search>

        <div class="moda-match-powered-by">
            Powered by <a href="https://moda-match.com" rel="nofollow" target="_blank">Moda Match</a>
        </div>

    </div>
    <!-- /.moda-match-full-height -->

</template>

<script>

import options from "../../options";

import axios from 'axios';

import Loader from "./Loader.vue";

import MeasuringTapeIcon from '../../../images/svg/icon-measuring-tape.svg?inline';
import RotateIcon from '../../../images/svg/3d_rotate.svg?inline';

import $ from 'jquery';

import NaturalSearch from "./NaturalSearch.vue";

export default {

    name: 'models',

    inject: [
        'getAsset',
        'setActiveModel',
        'getFittingRoomItems',
        'getApiToken',
        'track',
        'retrieveTheModels',
        'setTheModels'
    ],

    components: {

        Loader,
        MeasuringTapeIcon,
        NaturalSearch,
        RotateIcon
    },

    computed: {

        modelsToDisplay() {

          if( ! this.filteredModels || this.filteredModels.length < 1 ) {
            return this.models;
          }
          else {
            return this.filteredModels;
          }

        }

    },

    data() {

        return {

            isLoading: false,
            headerTitle: 'Models',
            models: [],
            filteredModels: null,
            activeModel: this.$parent.currentModel,
            showSizes: false,
            modelImageHeight: null,
            modelImageWidth: null,
            modelImageLoadingCompleted : false,
            currentSide: 'front',
            isRotateEnabled: options.is_rotate_enabled
        };
    },

    methods: {


        retrieveModels() {

            this.isLoading = true;

            let retrieveTheModels = this.retrieveTheModels();

            if ( retrieveTheModels.length ) {
              this.models = retrieveTheModels;
              if( this.models.length > 0 ) {
                if( ! this.activeModel ) {
                  this.activeModel = this.models[ 0 ];
                } else {
                  this.track( 'Model Viewed', 'Model', 'Viewed', this.activeModel.name );
                }
              }
              this.isLoading = false;
            } else {
              axios.get(
                options.api_url + 'models',
                {
                  headers: {
                    Authorization: 'Bearer ' + this.getApiToken()
                  }
                }
              )
              .then( ( response ) => {

                if( response && response.data ) {
                  response = response.data;
                }

                if( response.data ) {

                  let the_models = response.data;

                  if (the_models.length) {

                    let sizes = {
                      XXXS: 1,
                      XXS: 2,
                      XS: 3,
                      S: 4,
                      M: 5,
                      L: 6,
                      XL: 7,
                      XXL: 8,
                      XXXL: 9
                    };

                    let temp_array = [];

                    for ( var i = 0; i < the_models.length; i++ ) {
                      let the_model = the_models[i];
                      let the_model_sizes = the_model.size;

                      let the_largest_size = the_model_sizes[the_model_sizes.length-1];
                      let the_largest_size_value = sizes[the_largest_size];

                      for ( var j = 0; j < the_model_sizes.length; j++ ) {
                        let the_model_single_size       = the_model_sizes[j];
                        let the_model_single_size_value = sizes[the_model_single_size];
                        if ( the_model_single_size_value > the_largest_size_value ) {
                          the_largest_size = the_model_single_size;
                          the_largest_size_value = sizes[the_largest_size];
                        }
                      }

                      the_model.priority = the_largest_size_value;
                      temp_array.push(the_model);
                    }


                    temp_array.sort( function(a, b){ return a.priority - b.priority } );

                    the_models = temp_array;
                  }

                  this.models = the_models;

                  this.setTheModels( this.models );

                  if( this.models.length > 0 ) {
                    if( ! this.activeModel ) {
                      this.activeModel = this.models[ 0 ];
                    } else {
                      this.track( 'Model Viewed', 'Model', 'Viewed', this.activeModel.name );
                    }
                  }

                }
              } )
              .finally( () => {
                this.isLoading = false;
              } )
            }


        },

        selectModel( model ) {

            this.activeModel = model;
        },

        confirmModel() {

            this.setActiveModel( this.activeModel );

            let fittingRoomItems = this.getFittingRoomItems();

            if( fittingRoomItems.length > 0 ) {
                this.$router.push( 'fitting-room' );
            }
            else {
              alert( "Please select an item first" );
              this.$router.push( '/' );
            }
        },

        getSizeLabel( key ) {

            let label = null;

            switch( key ) {

                case 'neck':
                case 'chest':
                case 'waist':
                case 'hips':
                case 'bust':
                case 'thighs':

                    label = key.charAt(0).toUpperCase() + key.slice(1);

                    break;
                default:

                    label = key;

            }

            return label;
        },

        getFeetAndInchesStringFromCm (cmValue){
            let inches = (cmValue*0.393700787).toFixed(0);
            let feet = Math.floor(inches / 12);
            inches %= 12;
            return feet + "'" + inches + '"';
        },

        getSizePosition( key ) {

            let position = null;

            switch( key ) {

                case 'neck':

                    position = {
                        top: 120,
                        right: 56
                    };

                    break;
                case 'chest':

                    position = {
                        top: 190,
                        left: 45
                    };

                    break;
                case 'bust':

                    position = {
                        top: 200,
                        right: 57
                    };

                    break;
                case 'waist':

                    position = {
                        top: 267,
                        left: 50
                    };

                    break;
                case 'hips':

                    position = {
                        top: 309,
                        right: 45
                    };

                    break;
                case 'thighs':

                    position = {
                        top: 379,
                        left: 35
                    };

                    break;

            }

            if( position ) {

                position.top = ( position.top - 16 ) / 606 * 100 + '%';

                if( position.right ) {

                    position.right = ( position.right - 24 ) / 285 * 100 + '%';
                }

                if( position.left ) {

                    position.left = ( position.left - 24 ) / 285 * 100 + '%';
                }
            }

            return position;
        },

        toggleSizesDisplay() {

            if( this.currentSide !== 'front' ) {

                this.currentSide = 'front';
            }

            this.$nextTick( function() {
                this.refreshModelImageLoadingCompletedDataAndModelImageSize();

                this.$nextTick( function() {

                    this.showSizes = ! this.showSizes;

                    this.track( 'Measurements Selected', 'Measurements', 'Selected' );

                    this.track( 'Measurements Result', 'Measurements', 'Result', null, this.showSizes ? 1 : 0 );

                } );

            } )

        },

        refreshModelImageLoadingCompletedDataAndModelImageSize(){
            let container = this.$refs.modelImageContainer;

            if( container ) {
                let image = $( container ).find( 'img' );
                if( image.length > 0 ) {
                    //Model container has  images
                    const onImageLoaded = (function(){
                        //Model image finish loading
                        this.modelImageLoadingCompleted = true;
                        this.modelImageHeight = image.height();
                        this.modelImageWidth = image.width();
                    }).bind(this);

                    if(image.get(0).complete){
                        //Model image is already loaded
                        onImageLoaded();
                        return;
                    }else{
                        //Model image is not loaded yet
                        this.modelImageLoadingCompleted = false;
                        if(!image.get(0).onload){
                            image.get(0).onload = onImageLoaded;
                            //Model Image onload setup
                        }else{
                            //Model Image onload already setup
                        }
                        return;
                    }
                }else{
                    //Model container has no image
                    this.modelImageLoadingCompleted = false;

                }
            }else{
                //Model container is null
                this.modelImageLoadingCompleted = false;

            }
        },

        bindResize() {

            $( window ).resize( () => {
                this.refreshModelImageLoadingCompletedDataAndModelImageSize();

            } );
        },


        openNaturalSearch( trigger ) {

            this.$refs.naturalSearch.open( this.models, ( filteredModels ) => {

                this.filteredModels = filteredModels;

                if( this.filteredModels.length > 0 ) {

                    this.selectModel( _.first( this.filteredModels ) );
                }

            }, trigger );
        },

        resetFilters() {

            this.filteredModels = null;
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

                if( this.showSizes ) {

                    this.showSizes = false;
                }
            }
            else {

                this.currentSide = 'front';
            }

            this.track( 'Rotate Result', 'Rotate', 'Result', null, this.currentSide !== 'front' ? 1 : 0 );
        }
    },

    watch: {

        isLoading: function() {
          if ( !this.isLoading && this.models.length ) {
            setTimeout(() => {
              if ( this.$refs.hasOwnProperty( 'modaMatchModelViewerSizesToggle' ) && ( typeof this.$refs.modaMatchModelViewerSizesToggle != 'undefined' ) ) {
                this.$refs.modaMatchModelViewerSizesToggle.click();
              }
            }, 1000);
          }
        },

        activeModel: function() {

            if( this.activeModel ) {

                this.track( 'Model Viewed', 'Model', 'Viewed', this.activeModel.name );
                this.refreshModelImageLoadingCompletedDataAndModelImageSize();

            }
        },

    },

    mounted() {
        this.retrieveModels();

        this.bindResize();

    },

    updated(){
        this.refreshModelImageLoadingCompletedDataAndModelImageSize();
    }

};

</script>
