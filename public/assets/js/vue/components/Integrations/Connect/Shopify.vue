<template>

    <div class="align--center">

        <h1 class="heading heading--type-1 margin--b-20">{{integration.shopify_url}}</h1>
        <!-- /.heading heading--type-1 -->

        <a href="#" class="btn btn--green btn--large" v-on:click.prevent="connect()">Connect to Shopify</a>
        <!-- /.btn btn--green -->

    </div>

</template>

<script>

import axios from 'axios';

import options from '../../../../options';

export default {

    name: 'integration-connect-shopify',

    props: [

        'partner',
        'integration',
        'invite',
        'secret'
    ],

    data() {

        return {

            isLoading: false
        }
    },

    methods: {

        connect() {

            if( this.isLoading ) {

                return;
            }

            this.isLoading = true;

            axios.get(
                options.urls[ 'public.api.partners.integrations.shopify.connect' ]
                .replace( '%INVITE%', this.invite )
                + '?token=' + this.secret
            )
            .then( ( response ) => {

                if( response ) {

                    if( response.data ) {

                        response = response.data;
                    }

                    if( response.redirect ) {

                        document.location = response.redirect;
                    }
                }

            } )
            .finally( () => {

                this.isLoading = false;

            } );
        }
    }

};

</script>
