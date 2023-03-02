<template>

    <div class="moda-match-scroll">

        <div class="moda-match-contained">

            <div class="moda-match-cart" v-if="items.length > 0">

                <section class="moda-match-cart-section">

                    <div class="moda-match-cart-panel">

                        <header class="moda-match-cart-panel-header">

                            <h3 class="moda-match-cart-panel-title">Your bag</h3>
                            <!-- /.moda-match-cart-panel-title -->

                        </header>
                        <!-- /.moda-match-cart-panel-header -->

                        <footer class="moda-match-cart-panel-footer">

                            <div class="moda-match-cart-panel-footer-left">

                                Total ({{items.length}} {{  items.length > 1 ? 'items' : 'item' }}) <br>
                                {{formatPrice(subtotal)}}

                            </div>
                            <!-- /.moda-match-cart-panel-footer-left -->

                            <div class="moda-match-cart-panel-footer-right">

                                <button class="moda-match-btn moda-match-btn--black" type="button" v-on:click="checkout( 'Checkout' )" :disabled="isSubmitting">
                                    {{! isSubmitting ? 'Checkout' : 'Processing...'}}
                                </button>
                                <!-- /.moda-match-btn -->

                            </div>
                            <!-- /.moda-match-cart-panel-footer-right -->

                        </footer>
                        <!-- /.moda-match-cart-panel-footer -->

                    </div>
                    <!-- /.moda-match-cart-panel -->

                </section>
                <!-- /.moda-match-cart-section -->

                <section class="moda-match-cart-section">

                    <div class="moda-match-cart-items">

                        <article class="moda-match-cart-item" v-for="item in sortedItems" :key="item.source_product_id">

                            <figure
                                class="moda-match-cart-item-image"
                                v-if="item.activeVariant ? item.activeVariant.image : item.image"
                                v-html="item.activeVariant ? item.activeVariant.image.thumb : item.image.thumb"
                            ></figure>
                            <!-- /.moda-match-cart-item-image -->

                            <div v-else class="moda-match-cart-item-image"></div>

                            <div class="moda-match-cart-item-inner">

                                <header class="moda-match-cart-item-header">

                                    <h3 class="moda-match-cart-item-title">{{ item.activeVariant ? item.activeVariant.title : item.title }}</h3>
                                    <!-- /.moda-match-cart-item-title -->

                                    <span class="moda-match-cart-item-options">
                                        {{ formatOptions( item ) }}
                                    </span>
                                    <!-- /.moda-match-cart-item-options -->

                                </header>
                                <!-- /.moda-match-cart-item-header -->

                                <footer class="moda-match-cart-item-footer">

                                    <span class="moda-match-cart-item-price">{{formatPrice( item.activeVariant ? item.activeVariant.price : item.price )}}</span>
                                    <!-- /.moda-match-cart-item-price -->

                                </footer>
                                <!-- /.moda-match-cart-item-footer -->

                            </div>
                            <!-- /.moda-match-cart-item-inner -->


                            <div class="moda-match-cart-item-quantity">
                              <button class="" v-on:click="updateItem( item, 'mns', '- Icon' )">-</button><input type="number" :value="item.quantity" min="0" disabled /><button class="" v-on:click="updateItem( item, 'pls', '+ Icon' )">+</button>
                            </div>


                            <span class="moda-match-cart-item-total-price">{{formatPrice( item.activeVariant ? item.activeVariant.price * item.quantity : item.price * item.quantity )}}</span>

                            <a href="#" class="moda-match-cart-item-remove" v-on:click="removeItem( item, 'X Icon' )">x</a>
                            <!-- /.moda-match-cart-item-remove -->

                        </article>
                        <!-- /.moda-match-cart-item -->

                    </div>
                    <!-- /.moda-match-cart-items -->

                </section>
                <!-- /.moda-match-cart-section -->

                <section class="moda-match-cart-section">

                    <header class="moda-match-cart-section-header">

                        Order Summary

                    </header>
                    <!-- /.moda-match-cart-section-header -->

                    <footer class="moda-match-cart-panel">

                        <table class="moda-match-cart-summary">

                            <thead>

                            <tr colspan="2">
                                <th>{{items.length}} {{items.length > 1 ? 'items' : 'item'}}</th>
                            </tr>

                            </thead>

                            <tfoot>

                            <tr>
                                <th>
                                    Total
                                </th>
                                <td>
                                    <strong>{{formatPrice( subtotal )}}</strong>
                                </td>
                            </tr>

                            </tfoot>

                        </table>

                        <button class="moda-match-btn moda-match-btn--black moda-match-btn--block" v-on:click="checkout( 'Checkout' )" :disabled="isSubmitting">
                            {{! isSubmitting ? 'Checkout' : 'Processing...'}}
                        </button>
                        <!-- /.moda-match-btn moda-match-btn--block -->

                    </footer>
                    <!-- /.moda-match-cart-footer -->

                </section>
                <!-- /.moda-match-cart-section -->

            </div>

            <div v-else class="moda-match-full-height">

                <div class="moda-match-vertical-align">

                    <div class="moda-match-align--center">
                        Your cart is empty.
                    </div>

                </div>
                <!-- /.moda-match-vertical-align -->

            </div>

        </div>
        <!-- /.moda-match-contained -->

        <div class="moda-match-powered-by">
            Powered by <a href="https://moda-match.com" rel="nofollow" target="_blank">Moda Match</a>
        </div>

    </div>
    <!-- /.moda-match-scroll -->

</template>

<script>

import $ from 'jquery';

import _ from 'lodash';

import ProductOptions from "./ProductOptions.vue";
import axios from "axios";
import options from "../../options";

export default {

    name: 'cart',

    inject: [

        'getAsset',
        'getCartItems',
        'formatPrice',
        'removeCartItem',
        'updateCartItem',
        'getApiToken',
        'track'
    ],

    components: {
    },

    computed: {

        sortedItems() {

            return _.orderBy( this.items, 'title', 'asc' );
        },

        subtotal() {

            let subtotal = 0;

            for( let item of this.items ) {
                subtotal += item.price * item.quantity;
            }

            return subtotal;
        }
    },

    data() {

        return {

            isSubmitting: false,
            headerTitle: 'Cart',
            items: [],
        };
    },

    methods: {

        retrieveItems() {

            this.items = this.getCartItems();

            this.track( 'Cart Viewed', 'Cart', 'Viewed', null, this.subtotal );
        },

        formatOptions( item ) {

            let optionsFrom = null;

            if( item.activeVariant ) {

                optionsFrom = item.activeVariant;
            }
            else {

                optionsFrom = item;
            }

            let options = [];

            for( let i = 1; i < 5; i++ ) {

                if( typeof optionsFrom[ 'option' + i ] !== 'undefined' ) {

                    options.push( optionsFrom[ 'option' + i ] );
                }
            }

            return options.join( ' - ' );
        },

        updateItem( item, action, trigger ) {

          this.items = this.updateCartItem( item, action );

          if( trigger ) {

              this.track( 'Product Updated From Cart Button', 'Product', 'Clicked', trigger );
          }
        },

        removeItem( item, trigger ) {

            this.items = this.removeCartItem( item );

            if( trigger ) {

                this.track( 'Product Removed From Cart Button', 'Product', 'Clicked', trigger );
            }
        },

        checkout( trigger ) {

            if( this.isSubmitting ) {

                return;
            }

            this.isSubmitting = true;

            let product_ids = [];


            for( let item of this.items ) {
              product_ids.push({
                id: item.source_product_id,
                quantity: item.quantity,
                "properties" : {
                    "_referred_by": "Modamatch"
                }
              });
            }

            if( trigger ) {
              this.track( 'Checkout Button', 'Checkout', 'Clicked', trigger );
            }

            axios.post(
              '/cart/clear.js'
            )
            .then( ( clear_cart_response ) => {
              axios({
                method: 'post',
                url: '/cart/add.js',
                data: {
                  items: product_ids
                },
                validateStatus: (status) => {
                  return true;
                }
              })
              .then( ( response ) => {

                if( response && response.data ) {
                    response = response.data;
                }

                if( response.hasOwnProperty('items') && response.items.length ) {
                    this.track( 'Checkout Created', 'Checkout', 'Created', null, this.subtotal );
                    document.location = '/checkout';
                }

                if (response.hasOwnProperty('status') && response.status === 404) {
                  this.$notify( {
                      group: 'global',
                      title: response.description,
                      type: 'error'
                  } );
                  this.isSubmitting = false;
                }

                if (response.hasOwnProperty('status') && response.status === 422) {
                  this.$notify( {
                      group: 'global',
                      title: response.description,
                      type: 'error'
                  } );
                  this.isSubmitting = false;
                }

              } )
              .catch( (error_response) => {
                this.isSubmitting = false;

              } );
            } )
            .catch( () => {
              this.isSubmitting = false;
            } );

        }
    },

    created() {

        this.retrieveItems();
    },


};

</script>
