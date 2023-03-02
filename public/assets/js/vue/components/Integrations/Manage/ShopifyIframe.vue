<template>

    <div>

    </div>

</template>

<script>

import createApp from '@shopify/app-bridge';

export default {

    name: 'shopify-integration-manage',

    props: [

        'shop',
        'host',
        'apiKey',
    ],

    data() {

        return {
        };
    },

    methods: {

        init() {

            const app = createApp({
                apiKey: this.apiKey,
                host: this.host
            });
        }
    },

    mounted() {

        this.init();
    }

};

</script>