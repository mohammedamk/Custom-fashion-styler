<template>

    <div>

        <div class="moda-match-product-options">

            <header class="moda-match-product-options-header">

                <h3 class="moda-match-product-options-title">
                  {{ product.activeVariant ? product.activeVariant.title : product.title }} <br>
                  <a href="#" class="moda-match-product-options-view" v-on:click.prevent="openModal( 'Product Details' )">
                    Product Details
                  </a>
                  <!-- /.moda-match-product-options-view -->
                </h3>
                <!-- /.moda-match-product-options-title -->

            </header>
            <!-- /.moda-match-product-options-header -->

            <div class="moda-match-product-options-cart">

                <div class="moda-match-product-options-cart-left">

                    <header>Price</header>

                    <span>{{formatPrice( product.activeVariant ? product.activeVariant.price : product.price )}}</span>

                </div>
                <!-- /.moda-match-product-options-cart-left -->

                <div class="moda-match-product-options-cart-right display-on-desktop">

                    <button
                      class="moda-match-btn" v-on:click="addToCart( ! hasCartItem( product.activeVariant ? product.activeVariant : product ) ? 'Add to cart' : 'Added' )"
                      :class="{
                        'moda-match-btn--green': hasCartItem( product.activeVariant ? product.activeVariant : product ),
                        'moda-match-btn--black': !hasCartItem( product.activeVariant ? product.activeVariant : product ),
                        'moda-match-btn--size-small': true,
                      }"
                    >
                        {{! hasCartItem( product.activeVariant ? product.activeVariant : product ) ? 'Add to cart' : 'Added'}}
                    </button>
                    <!-- /.moda-match-btn -->

                </div>
                <!-- /.moda-match-product-options-cart-right -->

            </div>
            <!-- /.moda-match-product-options-cart -->

            <div class="moda-match-product-options-choices-list-and-button">
              <div
                class="moda-match-product-options-options-choices-list"
              >
                <div
                  class="moda-match-product-options-choices"
                  v-for="(choices, option) in product.options"
                  v-if="!product.unused_options_arr.includes(option)"
                >

                  <div class="moda-match-product-options-choice" v-for="choice in choices">

                    <input
                      :id="'product' + product.id + '_' + option + '_' + choice"
                      type="radio"
                      :name="'product' + '_' + option"
                      :value="choice"
                      v-model="selectedOptions[option]"
                      :disabled="!getVariantForOption( option, choice )"

                      v-if="getCurrentModel() && getCurrentModel().size.includes(choice)"
                    />

                    <label
                      :for="'product' + product.id + '_' + option + '_' + choice"
                      v-if="getCurrentModel() && getCurrentModel().size.includes(choice)"
                    >
                      {{choice}}
                    </label>

                  </div>
                  <!-- /.moda-match-product-options-choice -->
                </div>
                <!-- /.moda-match-product-options-choices -->
              </div>
              <!-- /.moda-match-product-options-options-choices-list -->


                 <div class="moda-match-product-options-choices-button-container moda-match-product-options-cart-right display-on-mobile" >

                        <button
                          class="moda-match-btn" v-on:click="addToCart( ! hasCartItem( product.activeVariant ? product.activeVariant : product ) ? 'Add to cart' : 'Added' )"
                          :class="{
                            'moda-match-btn--green': hasCartItem( product.activeVariant ? product.activeVariant : product ),
                            'moda-match-btn--black': !hasCartItem( product.activeVariant ? product.activeVariant : product ),
                            'moda-match-btn--size-small': true,
                          }"
                        >
                            {{! hasCartItem( product.activeVariant ? product.activeVariant : product ) ? 'Add to cart' : 'Added'}}
                        </button>
                        <!-- /.moda-match-btn -->

                </div>
                <!-- /.moda-match-product-options-choices-button-container -->
            </div>
            <!-- /.moda-match-product-options-options-choices-list-and-button -->

        </div>

        <modal :title="product.activeVariant ? product.activeVariant.title : product.title" ref="modal">

            <!-- <figure v-if="product.activeVariant ? product.activeVariant.image : product.image" class="moda-match-modal-image" v-html="product.activeVariant ? product.activeVariant.image.thumb : product.image.thumb"></figure> -->
            <div class="moda-match-modal-image-container">
              <!-- Carousel -->
              <ssr-carousel
                class="moda-match-modal-image"
                :class="{ 'moda-match-modal-image-full': !product.activeVariant.gallery.length }"
                show-arrows
                :slides-per-page='1'
                :paginate-by-slide='true'
                ref="carousel"
                v-on:change="onSlideChange"
                v-if="product.activeVariant"
              >

                <template v-if="!product.activeVariant.gallery.length">
                  <figure v-html="product.activeVariant.image.thumb" :index="0" v-bind:key="product.activeVariant.image.id"></figure>
                </template>
                <template v-else>
                  <figure v-for="(gallery_image, index) in product.activeVariant.gallery" v-html="gallery_image.thumb" :index="index" v-bind:key="gallery_image.id"></figure>
                </template>

                <template #back-arrow='{ disabled }'>
                  <svg class="image-gallery-svg" xmlns="http://www.w3.org/2000/svg" viewBox="6 0 12 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </template>
                <template #next-arrow='{ disabled }'>
                  <svg class="image-gallery-svg" xmlns="http://www.w3.org/2000/svg" viewBox="6 0 12 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </template>
              </ssr-carousel>

              <ssr-carousel
                class="moda-match-modal-image"
                :class="{ 'moda-match-modal-image-full': !product.gallery.length }"
                show-arrows
                :slides-per-page='1'
                :paginate-by-slide='true'
                ref="carousel"
                v-on:change="onSlideChange"
                v-else
              >

                <template v-if="!product.gallery.length">
                  <figure v-html="product.image.thumb" :index="0" v-bind:key="product.image.id"></figure>
                </template>
                <template v-else>
                  <figure v-for="(gallery_image, index) in product.gallery" v-html="gallery_image.thumb" :index="index" v-bind:key="gallery_image.id"></figure>
                </template>

                <template #back-arrow='{ disabled }'>
                  <svg class="image-gallery-svg" xmlns="http://www.w3.org/2000/svg" viewBox="6 0 12 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </template>
                <template #next-arrow='{ disabled }'>
                  <svg class="image-gallery-svg" xmlns="http://www.w3.org/2000/svg" viewBox="6 0 12 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </template>
              </ssr-carousel>
              <!-- Carousel END -->

              <!-- Gallery START -->
              <div class="moda-match-modal-image-gallery" ref="gallery" v-if="product.activeVariant && product.activeVariant.gallery.length">
                <figure v-for="(gallery_image, index) in product.activeVariant.gallery" v-bind:key="gallery_image.id" v-html="gallery_image.thumb" v-on:click.prevent="slideTo(index)" :class="{ 'moda-match-modal-image-gallery-active' : (index == active_index) }"></figure>
              </div>
              <div class="moda-match-modal-image-gallery" ref="gallery" v-if="!product.activeVariant && product.gallery.length">
                <figure v-for="(gallery_image, index) in product.gallery" v-bind:key="gallery_image.id" v-html="gallery_image.thumb" v-on:click.prevent="slideTo(index)" :class="{ 'moda-match-modal-image-gallery-active' : (index == active_index) }"></figure>
              </div>
              <!-- Gallery END -->

            </div>
            <!-- /.moda-match-modal-image -->

            <div class="moda-match-modal-price">{{formatPrice( product.activeVariant ? product.activeVariant.price : product.price )}}</div>
            <!-- /.moda-match-modal-price -->

            <div class="moda-match-modal-text" v-html="product.description"></div>
            <!-- /.moda-match-modal-text -->

            <footer class="moda-match-modal-footer">

                <button
                    class="moda-match-btn moda-match-btn--block moda-match-btn--size-medium"
                    :class="{
                    'moda-match-btn--secondary': hasCartItem( product.activeVariant ? product.activeVariant : product ),
                    }"
                    type="button"
                    v-on:click.prevent="addToCart( ! hasCartItem( product.activeVariant ? product.activeVariant : product ) ? 'Add to cart' : 'Remove from cart' )"
                >
                    {{! hasCartItem( product.activeVariant ? product.activeVariant : product ) ? 'Add to cart' : 'Remove from cart'}}
                </button>
                <!-- /.moda-match-btn modal-match-btn--block -->

            </footer>
            <!-- /.moda-match-modal-footer -->

        </modal>

    </div>

</template>

<script>

import $ from 'jquery';

import EyeIcon from '../../../images/svg/visibility_black_24dp.svg?inline';

import Modal from './Modal.vue';

import axios from 'axios';

import window_options from "../../options";

import SsrCarousel from 'vue-ssr-carousel';
import ssrCarouselCss from 'vue-ssr-carousel/index.css';

export default {

    name: 'product-options',

    props: [

        'product'
    ],

    components: {

        EyeIcon,
        Modal,
        SsrCarousel,
        ssrCarouselCss
    },

    data() {

        return {

            productData: this._extendData( this.product ),
            selectedOptions: this._selectedOptions( this.product ),
            models: [],
            currentModelChoices: [],
            active_index: 0

        };
    },

    inject: [

        'formatPrice',
        'setActiveVariantForItem',
        'addCartItem',
        'hasCartItem',
        'removeCartItem',
        'getFittingRoomItem',
        'track',
        'getApiToken',
        'getCurrentModel'
    ],

    methods: {

      retrieveModels() {

          // this.isLoading = true;

          axios.get(
              window_options.api_url + 'models',
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

                this.models = response.data;

                if( this.models.length > 0 ) {

                  for (var i = 0; i < this.models.length; i++) {
                    if ( this.getCurrentModel() && ( this.getCurrentModel().id === this.models[i].id ) ) {
                      this.currentModelChoices = this.models[i].size;
                      break;
                    }
                  }
                }
            }


          } )
          .finally( () => {

              // this.isLoading = false;

          } )
      },

      _extendData( data ) {

          return $.extend( {

              id: null,
              title: '',
              image: '',
              price: ''

          }, data );
      },

      _selectedOptions( product ) {

          let options = {

              option1: null,
              option2: null,
              option3: null,
              option4: null
          };

          if( product.activeVariant ) {

              for( let option in options ) {

                  if( options.hasOwnProperty( option ) ) {

                      if( typeof product.activeVariant[ option ] !== 'undefined' ) {

                          options[ option ] = product.activeVariant[ option ];
                      }
                  }
              }

          }

          return options;
      },

      getVariantForOption( option, choice ) {

          let options = {};

          options[ option ] = choice;

          options = _.defaults( options, this.selectedOptions );

          return this.getVariantForOptions( options );
      },

      getVariantForOptions( options ) {

          let found = null;

          for( let variant of this.product.variants ) {

              let matching = true;

              for( let option in options ) {

                  if( options.hasOwnProperty( option ) ) {

                      const optionValue = options[ option ];

                      if( typeof variant[ option ] !== 'undefined' && variant[ option ] !== optionValue ) {

                          matching = false;
                          break;
                      }
                  }
              }

              if( matching ) {

                  found = variant;
                  break
              }
          }

          return found;
      },

      selectProduct( options ) {

          let selectedVariant = this.getVariantForOptions( options );

          this.setActiveVariantForItem( this.product, selectedVariant );

      },

      addToCart( trigger ) {

          if( typeof window.navigator.vibrate !== 'undefined' ) {

              window.navigator.vibrate(100);
          }

          let productToAdd = this.product.activeVariant ? this.product.activeVariant : this.product;

          if( ! this.hasCartItem( productToAdd ) ) {

              if( trigger ) {

                  this.track( 'Product Added To Cart Button', 'Product', 'Clicked', trigger );
              }

              productToAdd.quantity = 1;
              this.addCartItem( productToAdd );
          }
          else {

              if( trigger ) {

                  this.track( 'Product Removed From Cart Button', 'Product', 'Clicked', trigger );
              }

              this.removeCartItem( productToAdd );
          }

          if( this.$refs.modal.isOpen ) {

              this.closeModal();
          }
      },

      displayOnModel() {

          this.product.isActive = ! this.product.isActive;

          this.$parent.calculateLayers( this.product );
      },

      openModal( trigger ) {

          if( trigger ) {

              this.track( 'Product Viewed Button', 'Product', 'Clicked', trigger );
          }

          this.track( 'Product Viewed', 'Product', 'Viewed', this.product.title );

          this.$refs.modal.open();
      },

      closeModal() {
          this.$refs.modal.close();
      },

      slideTo(the_index) {
        this.active_index = the_index;
        this.$refs.carousel.goto(the_index);
        this.scrollGallery(the_index);
      },

      onSlideChange({ index }) {
        this.active_index = index;
        this.scrollGallery(index);
      },

      scrollGallery(index) {
        this.$refs.gallery.scrollTop = 147 * index;
      }

    },

    watch: {

        'product.id': function() {

            this.selectedOptions = this._selectedOptions( this.product );

        },
        selectedOptions: {

            deep: true,
            handler: function() {

                this.selectProduct( this.selectedOptions );
            }
        }
    },

    mounted() {

      //this.retrieveModels();

    }

}

</script>
