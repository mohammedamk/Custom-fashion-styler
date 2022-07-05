<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">Integrations</h3>
            <!-- /.widget__title -->

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <form v-if="! formattedIntegration && ! sent" action="" class="" method="POST" v-on:submit.prevent="submitForm()">

                <alert-error :form="form" :message="formMessage"></alert-error>

                <div class="row">

                    <div class="col-sm-12">

                        <div class="form__group" :class="{ 'has-error': form.errors.has( 'store_type' ) }">

                            <label class="form__label">Store Type</label>
                            <!-- /.form__label -->

                            <div class="input__wrapper">

                                <div class="checkboxes">

                                    <div class="checkbox">

                                        <input
                                            id="input-store-type-shopify"
                                            type="radio"
                                            value="shopify"
                                            v-model="formData.store_type"
                                            name="partner_store_type"
                                        >

                                        <label for="input-store-type-shopify">Shopify</label>

                                    </div>
                                    <!-- /.checkbox -->

                                    <div class="checkbox">

                                        <input
                                            id="input-store-type-majento"
                                            type="radio"
                                            value="majento"
                                            v-model="formData.store_type"
                                            name="partner_store_type"
                                        >

                                        <label for="input-store-type-majento">Majento</label>

                                    </div>
                                    <!-- /.checkbox -->

                                </div>
                                <!-- /.checkboxes -->

                                <has-error :form="form" field="store_type"></has-error>

                            </div>
                            <!-- /.input__wrapper -->

                        </div>
                        <!-- /.form__group -->

                    </div>
                    <!-- /.col-sm-12 -->

                </div>
                <!-- /.row -->

                <div v-if="formData.store_type === 'shopify'">

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form__group" :class="{ 'has-error': form.errors.has( 'shopify_url' ) }">

                                <label for="input-shopify-store" class="form__label">Shopify Store</label>
                                <!-- /.form__label -->

                                <div class="input__wrapper">

                                    <input
                                        id="input-shopify-store"
                                        type="string"
                                        class="form__control"
                                        placeholder="mystore.myshopify.com"
                                        v-model="formData.shopify_url"
                                    >
                                    <!-- /.form__control -->

                                    <has-error :form="form" field="shopify_url"></has-error>

                                </div>
                                <!-- /.input__wrapper -->

                            </div>
                            <!-- /.form__group -->

                        </div>
                        <!-- /.col-sm-12 -->

                    </div>
                    <!-- /.row -->

                    <h3 class="heading heading--type-2 margin--b-15">Private App</h3>
                    <!-- /.form__heading -->

                    <div class="text text--type-2 margin--b-20">A private app access token will provide products import capability before the client install the Sales Channel.</div>
                    <!-- /.form__description -->

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form__group form__group--stacked" :class="{ 'has-error': form.errors.has( 'api_key' ) }">

                                <label for="input-shopify-api-key" class="form__label">API Key</label>
                                <!-- /.form__label -->

                                <div class="input__wrapper">

                                    <input
                                        id="input-shopify-api-key"
                                        type="password"
                                        class="form__control"
                                        placeholder="Paste the API Key here..."
                                        v-model="formData.api_key"
                                    >
                                    <!-- /.form__control -->

                                    <has-error :form="form" field="api_key"></has-error>

                                </div>
                                <!-- /.input__wrapper -->

                            </div>
                            <!-- /.form__group -->

                        </div>
                        <!-- /.col-sm-12 -->

                    </div>
                    <!-- /.row -->

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form__group form__group--stacked" :class="{ 'has-error': form.errors.has( 'api_secret' ) }">

                                <label for="input-shopify-api-secret" class="form__label">API Secret</label>
                                <!-- /.form__label -->

                                <div class="input__wrapper">

                                    <input
                                        id="input-shopify-api-secret"
                                        type="password"
                                        class="form__control"
                                        placeholder="Paste the API Key here..."
                                        v-model="formData.api_secret"
                                    >
                                    <!-- /.form__control -->

                                    <has-error :form="form" field="api_secret"></has-error>

                                </div>
                                <!-- /.input__wrapper -->

                            </div>
                            <!-- /.form__group -->

                        </div>
                        <!-- /.col-sm-12 -->

                    </div>
                    <!-- /.row -->

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="form__group form__group--stacked" :class="{ 'has-error': form.errors.has( 'access_token' ) }">

                                <label for="input-shopify-store" class="form__label">Access Token</label>
                                <!-- /.form__label -->

                                <div class="input__wrapper">

                                    <input
                                        id="input-shopify-access-token"
                                        type="password"
                                        class="form__control"
                                        placeholder="Paste the token here..."
                                        v-model="formData.access_token"
                                    >
                                    <!-- /.form__control -->

                                    <has-error :form="form" field="access_token"></has-error>

                                </div>
                                <!-- /.input__wrapper -->

                            </div>
                            <!-- /.form__group -->

                        </div>
                        <!-- /.col-sm-12 -->

                    </div>
                    <!-- /.row -->

                </div>

                <footer class="form__footer">

                    <div></div>

                    <div class="form__footer--right">

                        <button class="btn" type="submit" :disabled="isSubmitting">Save</button>
                        <!-- /.btn -->

                    </div>

                </footer>
                <!-- /.form__footer -->

            </form>
            <!-- /. -->

            <div v-else-if="formattedIntegration && ! sent">

                <table class="table table--vertical">

                    <tbody>

                    <tr>

                        <th>Store Type</th>

                        <td>{{formattedIntegration.store_type}}</td>

                    </tr>

                    <tr v-if="formattedIntegration.store_type === 'shopify'">

                        <th>Shopify Store</th>

                        <td>{{formattedIntegration.shopify_store}}</td>

                    </tr>

                    <tr>

                        <th>Status</th>

                        <td>{{formattedIntegration.status}}</td>

                    </tr>

                    <tr>

                        <th>Last Updated</th>

                        <td>{{formatDate( formattedIntegration.last_updated )}}</td>

                    </tr>

                    <tr>

                        <th>API Token</th>

                        <td>{{formattedIntegration.partner_app_token}}</td>

                    </tr>

                    <tr v-if="formattedEmbedCode">

                        <th>Embed Code</th>

                        <td>
                            <code class="language-html" v-html="formattedEmbedCode"></code>
                        </td>

                    </tr>

                    <tr v-if="formattedButtonCode">

                        <th>Button Code</th>

                        <td>
                            <code class="language-html" v-html="formattedButtonCode"></code>
                        </td>

                    </tr>

                    </tbody>

                </table>
                <!-- /.table -->

                <div class="margin--t-20">

                    <button class="btn btn--button btn--wireframe" type="button" v-on:click.prevent="reset()">Edit</button>
                    <!-- /.btn btn--button btn--wireframe -->

                </div>
                <!-- /.margin--t-20 -->

            </div>

            <div v-else>

                <div class="empty-text color--blue">

                    The integration has been updated.

                </div>
                <!-- /.empty-text -->

            </div>

        </div>
        <!-- /.widget__content -->

    </div>
    <!-- /.widget -->

</template>


<script>

import {Form, HasError, AlertError} from 'vform';

import options from '../../../options';

import Loader from "../Modules/Loader.vue";

import _ from 'lodash';

import Prism from 'prismjs';
import 'prismjs/themes/prism.css';

export default {

    name: 'partner-form',

    props: {

        partner: {

            required: true,
            type: Object,
            default: function() {
                return {};
            }
        },

        integration: {

            required: false,
            type: Object,
            default: function() {
                return null;
            }
        },
    },

    computed: {

        formattedEmbedCode() {

            if( this.partner.embed_code ) {

                return Prism.highlight( this.partner.embed_code, Prism.languages.html, 'html' );
            }

            return '';
        },

        formattedButtonCode() {

            if( this.formattedIntegration.button_code ) {

                return Prism.highlight( this.formattedIntegration.button_code, Prism.languages.html, 'html' );
            }

            return '';
        },
    },

    components: {

        HasError,
        AlertError,
        Loader
    },

    data() {

        return {

            isSubmitting: false,
            form: new Form( this._extendData( {} ) ),
            formData: this._extendData( this.integration && this.integration.model ? this.integration.model : {} ),
            formMessage: null,
            sent: false,
            sentTimeout: null,
            formattedIntegration: this.integration ? this._formatIntegration( this.integration || {} ) : null
        };
    },

    inject: [

        'formatDate'
    ],

    methods: {

        _formatIntegration( integration ) {

            return _.extend( {

                store_type: '',
                status: '-',
                last_updated: false,
                model: {}
            }, integration );
        },

        _extendData( data ) {

            return _.extend( {

                store_type: 'shopify',
                shopify_url: null,
                access_token: '',
                api_key: '',
                api_secret: ''

            }, data );
        },

        submitForm() {

            if( this.isSubmitting || this.formattedIntegration ) {

                return;
            }

            this.isSubmitting = true;
            this.formMessage = null;

            this.form.fill( this.formData );

            this.form.post(
                options.urls[ 'api.partners.integrations.invite' ]
                    .replace( '%PARTNER%', this.partner.id )
            ).then( ( response ) => {

                if( response && response.data ) {

                    response = response.data;
                }

                if( response.success ) {

                    this.sent = true;

                    if( this.sentTimeout ) {

                        clearTimeout( this.sentTimeout );
                    }

                    this.sentTimeout = setTimeout( () => {

                        this.sent = false;

                    }, 4000 );

                    this.$notify( {

                        group: 'global',
                        title: 'Sent!',
                        type: 'success'
                    } );

                    if( response.integration ) {

                        this.formattedIntegration = this._formatIntegration( response.integration );
                    }
                }

                if( response.redirect ) {

                    this.$notify( {

                        group: 'global',
                        title: 'Redirecting...',
                        type: 'info'
                    } );

                    document.location.href = response.redirect;
                }
            } )
                .catch( ( err ) => {

                    if( err && err.response ) {

                        let response = err.response;

                        if( response.data ) {

                            response = response.data;
                        }

                        if( response.message ) {

                            this.formMessage = response.message;
                        }
                    }
                } )
                .finally( () => {

                    this.isSubmitting = false;

                } );
        },

        reset() {

            this.formattedIntegration = null;
        }
    },
}

</script>
