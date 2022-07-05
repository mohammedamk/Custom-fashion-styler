<template>

    <tabs>

        <tab title="All">

            <items-grid></items-grid>

        </tab>

        <tab v-for="collection in collections" :title="collection.name" v-bind:key="collection.id">

            <items-grid :collection="collection.id" :collection-label="collection.name"></items-grid>

        </tab>

    </tabs>

</template>

<script>

import Tabs from './Tabs.vue';
import Tab from './Tab.vue';

import ItemsGrid from "./ItemsGrid.vue";

import $ from 'jquery';

import axios from "axios";
import options from "../../options";

export default {

    name: 'collections',

    components: {

        Tabs,
        Tab,
        ItemsGrid
    },

    inject: [

        'getApiToken',
        'retrieveTheCollections',
        'setTheCollections'
    ],

    data() {

        return {

            collections: []
        };
    },

    methods: {

        _extendData( collection ) {

            return $.extend( {

                id: null,
                name: null,

            }, collection );
        },

        retrieveCollections() {

          let retrieveTheCollections = this.retrieveTheCollections();

          if ( retrieveTheCollections.length ) {
            this.collections = retrieveTheCollections;
          } else {
            axios.get(
              options.api_url + 'categories',
              {
                headers: {
                  Authorization: 'Bearer ' + this.getApiToken()
                }
              }
            ).then( ( response ) => {

              if( response && response.data ) {
                response = response.data;
              }

              let collections = response.data;

              for ( let collection of collections ) {
                this.collections.push( this._extendData( collection ) );
              }

              this.setTheCollections( this.collections );

            } )
          }


        }
    },

    mounted() {

        this.retrieveCollections();
    }
}

</script>
