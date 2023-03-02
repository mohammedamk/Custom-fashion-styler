<template>

    <div>

    </div>

</template>

<script>

import createApp from '@shopify/app-bridge';

import { Redirect } from '@shopify/app-bridge/actions';

export default {

    name: 'shopify-integration-iframe',

    props: [

        'shop',
        'host',
        'apiKey',
        'redirectUri',
        'scopes'
    ],

    data() {

        return {


        };
    },

    methods: {

        init() {

            const permissionUrl = `https://${this.shop}/admin/oauth/authorize?client_id=${this.apiKey}&scope=read_products,read_content,read_checkouts,write_checkouts&redirect_uri=${this.redirectUri}`;

            if (window.top == window.self) {

                window.location.assign(permissionUrl);
            } else {

                const app = createApp({
                    apiKey: this.apiKey,
                    host: this.host
                });
                Redirect.create(app).dispatch(Redirect.Action.REMOTE, permissionUrl);
            }

        }
    },

    mounted() {

        this.init();
    }

};

</script>
