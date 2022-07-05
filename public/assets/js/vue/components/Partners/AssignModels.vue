<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">Models</h3>
            <!-- /.widget__title -->

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <form action="" class="" method="POST" v-on:submit.prevent="submitForm()">

                <alert-error :form="form" :message="formMessage"></alert-error>

                <div class="row">

                    <div class="col-sm-12">

                        <div class="form__group" :class="{ 'has-error': form.errors.has( 'store_type' ) }">

                            <div class="checkboxes margin--t-28">

                                <div class="checkbox" v-for="model in models" v-bind:key="model.id">

                                    <input
                                        :id="'input-model-' + model.id"
                                        type="checkbox"
                                        :value="model.id"
                                        v-model="formData.models"
                                        name="partner_models[]"
                                    >

                                    <label :for="'input-model-' + model.id">{{ model.name }}</label>

                                </div>
                                <!-- /.checkbox -->

                            </div>
                            <!-- /.checkboxes -->

                            <has-error :form="form" field="models"></has-error>

                        </div>
                        <!-- /.form__group -->

                    </div>
                    <!-- /.col-sm-12 -->

                </div>
                <!-- /.row -->

                <hr class="margin--v-30">
                <!-- /.margin--v-30 -->

                <div class="row">

                    <div class="col-sm-12">

                        <div class="form__group" :class="{ 'has-error': form.errors.has( 'is_rotate_enabled' ) }">

                            <label class="form__label">Show rotate?</label>
                            <!-- /.form__label -->

                            <div class="input__wrapper">

                                <div class="checkboxes">

                                    <div class="checkbox">

                                        <input
                                            id="input-rotate-enabled-yes"
                                            type="radio"
                                            :value="1"
                                            v-model="formData.is_rotate_enabled"
                                            name="is_rotate_enabled"
                                        >

                                        <label for="input-rotate-enabled-yes">Yes</label>

                                    </div>
                                    <!-- /.checkbox -->

                                    <div class="checkbox">

                                        <input
                                            id="input-is-rotate-enabled-no"
                                            type="radio"
                                            :value="0"
                                            v-model="formData.is_rotate_enabled"
                                            name="is_rotate_enabled"
                                        >

                                        <label for="input-is-rotate-enabled-no">No</label>

                                    </div>
                                    <!-- /.checkbox -->

                                </div>
                                <!-- /.checkboxes -->

                                <has-error :form="form" field="is_rotate_enabled"></has-error>

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

                        <div class="form__group" :class="{ 'has-error': form.errors.has( 'is_tuck_in_enabled' ) }">

                            <label class="form__label">Show tuck in?</label>
                            <!-- /.form__label -->

                            <div class="input__wrapper">

                                <div class="checkboxes">

                                    <div class="checkbox">

                                        <input
                                            id="input-tuck-in-enabled-yes"
                                            type="radio"
                                            :value="1"
                                            v-model="formData.is_tuck_in_enabled"
                                            name="is_tuck_in_enabled"
                                        >

                                        <label for="input-tuck-in-enabled-yes">Yes</label>

                                    </div>
                                    <!-- /.checkbox -->

                                    <div class="checkbox">

                                        <input
                                            id="input-is-tuck-in-enabled-no"
                                            type="radio"
                                            :value="0"
                                            v-model="formData.is_tuck_in_enabled"
                                            name="is_tuck_in_enabled"
                                        >

                                        <label for="input-is-tuck-in-enabled-no">No</label>

                                    </div>
                                    <!-- /.checkbox -->

                                </div>
                                <!-- /.checkboxes -->

                                <has-error :form="form" field="is_rotate_enabled"></has-error>

                            </div>
                            <!-- /.input__wrapper -->

                        </div>
                        <!-- /.form__group -->

                    </div>
                    <!-- /.col-sm-12 -->

                </div>
                <!-- /.row -->

                <footer class="form__footer">

                    <div class="form__footer--left">

                        <span class="form__status">
                        <span v-if="isSubmitting">
                            <loader></loader> Saving models...
                        </span>
                        <span v-if="saved" class="color--green">
                            Saved.
                        </span>
                        </span>

                    </div>
                    <!-- /.form__footer--left -->

                    <div class="form__footer--right">

                        <button class="btn" type="submit" :disabled="isSubmitting">Save</button>
                        <!-- /.btn -->

                    </div>
                    <!-- /.form__footer--right -->

                </footer>
                <!-- /.form__footer -->

            </form>
            <!-- /. -->

        </div>
        <!-- /.widget__content -->

    </div>
    <!-- /.widget -->

</template>


<script>

import axios from 'axios';

import {Form, HasError, AlertError} from 'vform';

import options from '../../../options';

import Loader from "../Modules/Loader.vue";

import _ from 'lodash';

export default {

    name: 'partner-models-form',

    props: {

        partner: {

            required: true,
            type: Object,
            default: function() {
                return {};
            }
        },
    },

    components: {

        HasError,
        AlertError,
        Loader
    },

    data() {

        let models = [];

        if( this.partner && this.partner.models ) {

            for ( let model of this.partner.models ) {

                models.push( model.id );
            }
        }

        return {

            isSubmitting: false,
            form: new Form( this._extendData( {} ) ),
            formData: this._extendData( {

                models: models,
                is_rotate_enabled: this.partner.is_rotate_enabled,
                is_tuck_in_enabled: this.partner.is_tuck_in_enabled
            } ),
            models: [],
            formMessage: null,
            saved: false,
            savedTimeout: null
        };
    },

    methods: {

        _extendData( data ) {

            return _.extend( {

                models: [],
                is_rotate_enabled: false,
                is_tuck_in_enabled: false

            }, data );
        },

        retrieveModels() {

            axios.get(
                options.urls[ 'api.models.list' ]
            )
            .then( ( response ) => {

                if( response ) {

                    if( response.data ) {

                        response = response.data;
                    }

                    if( response.data ) {

                        this.models = response.data;
                    }
                }

            } );

        },

        submitForm() {

            if( this.isSubmitting ) {

                return;
            }

            this.isSubmitting = true;
            this.formMessage = null;

            this.form.fill( this.formData );

            this.form.put(
                options.urls[ 'api.partners.models' ]
                    .replace( '%PARTNER%', this.partner.id )
            ).then( ( response ) => {

                if( response && response.data ) {

                    response = response.data;
                }

                if( response.success ) {

                    this.saved = true;

                    if( this.savedTimeout ) {

                        clearTimeout( this.savedTimeout );
                    }

                    this.savedTimeout = setTimeout( () => {

                        this.saved = false;

                    }, 4000 );

                    this.$notify( {

                        group: 'global',
                        title: 'Models saved!',
                        type: 'success'
                    } );
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
        }
    },

    mounted() {

        this.retrieveModels();
    }
}

</script>
