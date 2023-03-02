<template>

    <div>

        <div class="moda-match-items-grid" v-if="!isLoading">

            <div class="moda-match-items-grid-item" v-for="item in items" v-bind:key="item.id">

                <item-card :item="item"></item-card>

            </div>
            <!-- /.moda-match-items-grid-item -->

        </div>
        <!-- /.moda-match-items-grid -->

        <div class="moda-match-vertical-align" v-else>

            <loader></loader>

        </div>
        <!-- /.moda-match-vertical-align -->

        <div class="moda-match-powered-by" v-if="!isLoading">
            Powered by <a href="https://moda-match.com" rel="nofollow" target="_blank">Moda Match</a>
        </div>

    </div>


</template>

<script>

import ItemCard from "./ItemCard.vue";

import axios from 'axios';

import options from '../../options';

import $ from 'jquery';

import Loader from "./Loader.vue";

export default {

    name: 'items-grid',

    props: [

        'collection',
        'collection-label'
    ],

    components: {

        ItemCard,
        Loader
    },

    inject: [

        'getFittingRoomItem',
        'getApiToken',
        'createProductObject',
        'track',
        'retrieveTheProducts',
        'setTheProducts'
    ],

    data() {

        return {
            isLoading: false,
            items: []
        };
    },

    methods: {

        retrieveItems() {

            this.isLoading = true;

            let apiUrl = null;

            // if( this.collection ) {
            //   apiUrl = options.api_url + 'categories/' + this.collection + '/products'
            // } else {
              apiUrl = options.api_url + 'products';
            // }

            //
            let setTheProducts = this.retrieveTheProducts();
            if ( setTheProducts.length ) {
              if( !this.collection ) {
                  this.items = setTheProducts;
              } else {
                for (var i = 0; i < setTheProducts.length; i++) {
                  let the_product_obj = setTheProducts[i];
                  if ( the_product_obj.category.id == this.collection ) {
                    this.items.push( the_product_obj );
                  }
                }
                this.track( 'Category Selected', 'Category', 'Selected', this.collectionLabel );
              }
              this.isLoading = false;
            } else {
              axios.get(
                apiUrl,
                {
                  headers: {
                    Authorization: 'Bearer ' + this.getApiToken()
                  }
                }
              ).then( ( response ) => {

                if( response && response.data ) {
                  response = response.data;
                }

                let items = response.data;

                for ( let item of items ) {
                  this.items.push( this.createProductObject( item ) );
                }

                //
                this.setTheProducts( this.items );
                //

                if( this.collection ) {
                  this.track( 'Category Selected', 'Category', 'Selected', this.collectionLabel );
                }

              } )
              .finally( () => {
                this.isLoading = false;
              } );

            }
            //


        },
    },

    created() {

        this.retrieveItems();
    }

};

</script>
