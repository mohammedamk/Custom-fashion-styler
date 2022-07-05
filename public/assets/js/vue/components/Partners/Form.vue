<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">New Partner</h3>
            <!-- /.widget__title -->

        </header>
        <!-- /.widget__header -->

        <form class="margin--t-41" action="" method="POST" v-on:submit.prevent="submitForm()">

            <alert-error :form="form" :message="formMessage"></alert-error>

            <div class="row">

                <div class="col-sm-12">

                    <div class="form__group" :class="{'has-error': form.errors.has( 'name' )}">

                        <label for="input-name" class="form__label">Name</label>
                        <!-- /.form__label -->

                        <div class="input__wrapper">

                            <input
                                id="input-name"
                                type="text"
                                class="form__control"
                                name="partner_name"
                                v-model="formData.name"
                                placeholder="Partner name..."
                            >
                            <!-- /.form__control -->

                            <div class="help-block" v-if="! form.errors.has( 'name' )">Enter the name of the partner’s store</div>
                            <!-- /.help-block -->

                            <has-error :form="form" field="name"></has-error>

                        </div>
                        <!-- /.input__wrapper -->

                    </div>
                    <!-- /.form__group -->

                </div>
                <!-- /.col-sm-12 -->

            </div>
            <!-- /.row -->

            <div class="row" v-if="isEdit">

                <div class="col-sm-12">

                    <div class="form__group" :class="{ 'has-error': form.errors.has( 'is_pending' ) }">

                        <label class="form__label">Approved?</label>
                        <!-- /.form__label -->

                        <div class="input__wrapper">

                            <div class="checkboxes">

                                <div class="checkbox">

                                    <input
                                        id="input-status-approved"
                                        type="radio"
                                        :value="0"
                                        v-model="formData.is_pending"
                                        name="partner_status"
                                    >

                                    <label for="input-status-approved">Yes</label>

                                </div>
                                <!-- /.checkbox -->

                                <div class="checkbox">

                                    <input
                                        id="input-status-pending"
                                        type="radio"
                                        :value="1"
                                        v-model="formData.is_pending"
                                        name="partner_status"
                                    >

                                    <label for="input-status-pending">No</label>

                                </div>
                                <!-- /.checkbox -->

                            </div>
                            <!-- /.checkboxes -->

                            <has-error :form="form" field="is_pending"></has-error>

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

                    <div class="form__group" :class="{'has-error': form.errors.has( 'email' )}">

                        <label for="input-email" class="form__label">Email</label>
                        <!-- /.form__label -->

                        <div class="input__wrapper">

                            <input
                                id="input-email"
                                type="email"
                                class="form__control"
                                name="partner_email"
                                v-model="formData.email"
                                placeholder="email@email.com"
                            >
                            <!-- /.form__control -->

                            <div class="help-block" v-if="! form.errors.has( 'email' )">Enter the contact Email</div>
                            <!-- /.help-block -->

                            <has-error :form="form" field="email"></has-error>

                        </div>
                        <!-- /.input__wrapper -->

                    </div>
                    <!-- /.form__group -->

                </div>
                <!-- /.col-sm-12 -->

            </div>
            <!-- /.row -->

            <div class="form__spacer"></div>
            <!-- /.form__spacer -->

            <div class="row">

                <div class="col-sm-12">

                    <div class="form__group" :class="{'has-error': form.errors.has( 'primary_color' )}">

                        <label for="input-primary-color" class="form__label">Primary Color</label>
                        <!-- /.form__label -->

                        <div class="input__wrapper">

                            <input
                                id="input-primary-color"
                                type="text"
                                class="form__control"
                                name="partner_primary_color"
                                v-model="formData.primary_color"
                                placeholder="#111111"
                            >
                            <!-- /.form__control -->

                            <div class="help-block" v-if="! form.errors.has( 'primary_color' )">HEX Color Code</div>
                            <!-- /.help-block -->

                            <has-error :form="form" field="primary_color"></has-error>

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

                    <div class="form__group" :class="{'has-error': form.errors.has( 'secondary_color' )}">

                        <label for="input-secondary-color" class="form__label">Secondary Color</label>
                        <!-- /.form__label -->

                        <div class="input__wrapper">

                            <input
                                id="input-secondary-color"
                                type="text"
                                class="form__control"
                                name="partner_secondary_color"
                                v-model="formData.secondary_color"
                                placeholder="#111111"
                            >
                            <!-- /.form__control -->

                            <div class="help-block" v-if="! form.errors.has( 'secondary_color' )">HEX Color Code</div>
                            <!-- /.help-block -->

                            <has-error :form="form" field="secondary_color"></has-error>

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

                    <div class="form__group" :class="{'has-error': form.errors.has( 'is_font_enabled' )}">

                        <label for="input-secondary-color" class="form__label" :data-formData="JSON.stringify(formData)">Use Moda Match Font</label>
                        <!-- /.form__label -->

                        <div class="input__wrapper">

                            <div class="checkboxes">

                                <div class="checkbox">

                                    <input
                                        id="input-font-enabled"
                                        type="radio"
                                        :value="1"
                                        v-model="formData.is_font_enabled"
                                        name="is_font_enabled"
                                    >

                                    <label for="input-font-enabled">Yes</label>

                                </div>
                                <!-- /.checkbox -->

                                <div class="checkbox">

                                    <input
                                        id="input-font-disabled"
                                        type="radio"
                                        :value="0"
                                        v-model="formData.is_font_enabled"
                                        name="is_font_enabled"
                                    >

                                    <label for="input-font-disabled">No</label>

                                </div>
                                <!-- /.checkbox -->

                            </div>
                            <!-- /.checkboxes -->

                            <has-error :form="form" field="is_font_enabled"></has-error>

                        </div>
                        <!-- /.input__wrapper -->

                    </div>
                    <!-- /.form__group -->

                </div>
                <!-- /.col-sm-12 -->

            </div>
















            <footer class="form__footer">

                <div class="form__footer--left">

                    <div class="form__status">
                        <span v-if="isSubmitting">
                            <loader></loader> Saving
                        </span>
                        <span v-else-if="saved" class="color--green">
                            Saved!
                        </span>
                    </div>

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

    </div>
    <!-- /.widget -->

</template>

<script>

import {Form, HasError, AlertError} from 'vform';

import options from '../../../options';

import Loader from "../Modules/Loader.vue";

import _ from 'lodash';

export default {

    name: 'partner-form',

    props: {

        partner: {

            required: false,
            type: Object,
            default: function() {
                return {};
            }
        }
    },

    components: {

        HasError,
        AlertError,
        Loader
    },

    data() {

        return {

            isSubmitting: false,
            isEdit: this.partner && this.partner.id,
            form: new Form( this._extendData( {} ) ),
            formData: this._extendData( this.partner || {} ),
            formMessage: null,
            saved: false,
            savedTimeout: null,
        };
    },

    methods: {

        _extendData( data ) {

            return _.extend( {

                name: null,
                email: null,
                primary_color: null,
                secondary_color: null,
                is_pending: this.isEdit ? 1 : 0,
                is_font_enabled: 0

            }, data );
        },

        submitForm() {

            if( this.isSubmitting ) {
                return;
            }

            this.isSubmitting = true;
            this.formMessage = null;

            if( this.formData.primary_color ) {

                this.formData.primary_color = this.formData.primary_color.replace( '#', '' );
            }

            if( this.formData.secondary_color ) {

                this.formData.secondary_color = this.formData.secondary_color.replace( '#', '' );
            }

            this.form.fill( this.formData );

            let formRequest = null;

            if( ! this.isEdit ) {

                formRequest = this.form.post(
                    options.urls[ 'api.partners.create' ]
                );
            }
            else {

                formRequest = this.form.put(
                    options.urls[ 'api.partners.edit' ].replace( '%PARTNER%', this.partner.id )
                );
            }

            formRequest.then( ( response ) => {

                if( response && response.data ) {

                    response = response.data;
                }

                if( response.success ) {

                    this.saved = true;

                    if( this.savedTimeout ) {

                        clearTimeout(this.savedTimeout);
                    }

                    this.savedTimeout = setTimeout( () => {

                        this.saved = false;

                    }, 2000 );

                    this.$notify( {

                        group: 'global',
                        title: 'Saved!',
                        type: 'success'
                    } );

                    if( response.partner ) {

                        this.formData = this._extendData( response.partner );
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
        }
    }
}

</script>
