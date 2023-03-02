if( window.Vue === undefined ) {

    window.Vue = require('vue');

}

import VueRouter from 'vue-router';

Vue.use( VueRouter );

import App from './components/App.vue';
import Collections from "./components/Collections.vue";
import Models from "./components/Models.vue";
import FittingRoom from "./components/FittingRoom.vue";
import Cart from "./components/Cart.vue";

import Notifications from 'vue-notification';

Vue.use( Notifications );

import VModal from 'vue-js-modal'
Vue.use(VModal)

class Bootstrap {

    constructor( embed, apiToken ) {

        const routes = [
            {
                path: '/models',
                component: Models,
                meta: {

                    title: 'Models',
                }
            },
            {
                path: '/',
                component: Collections,
                meta: {

                    title: 'Catalog',
                }
            },
            {
                name: 'fitting-room',
                path: '/fitting-room',
                component: FittingRoom,
                meta: {

                    title: 'Fitting Room',
                }
            },
            {
                path: '/cart',
                component: Cart,
                meta: {

                    title: 'Cart'
                }
            }
        ];

        const router = new VueRouter({
            routes: routes,
        });

        this.vue = new Vue( {

            router,

            el: '#moda-match-embed',

            data() {

                return {

                    embed: embed,
                    apiToken: apiToken
                };
            },

            components: {

                App,
                Collections,
                Models
            },

            methods: {

                getEmbed() {

                    return this.embed;
                },

                getApiToken() {

                    return this.apiToken;
                },
            },

            provide() {

              return {

                  getEmbed: this.getEmbed,
                  getApiToken: this.getApiToken,
                  track: ( event, category, action, label, value ) => this.getEmbed().track( event, category, action, label, value )
              };
            },

            created() {

                window.ModaMatch = this;

            },
        } );
    }

    setDeepLinkProduct( id ) {

        this.vue.$refs.app.setDeepLinkProduct( id );

    }

}

module.exports = Bootstrap;
