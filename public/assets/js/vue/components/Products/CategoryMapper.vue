<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">Categories</h3>
            <!-- /.widget__title -->

            <loader v-if="isLoading"></loader>

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <form v-if="! isLoading" action="" class="margin--t-36" method="POST" v-on:submit.prevent="submitForm()">

                <alert-error :form="form" :message="formMessage"></alert-error>

                <div class="row" v-for="category in categories" v-bind:key="category.id">

                    <div class="col-sm-12">

                        <div class="form__group" :class="{ 'has-error': form.errors.has( 'categories.' + category.id + '.layer' ) }">

                            <label :for="'input-category-' + category.id + '-layer'" class="form__label">{{ category.name }}</label>
                            <!-- /.form__label -->

                            <div class="input__wrapper">

                                <select
                                    :id="'input-category-' + category.id + '-layer'"
                                    class="form__control form__control--type-select"
                                    v-model="formData.categories[ category.id ].layer"
                                >

                                    <option :value="null">Choose a layer...</option>
                                    <option :value="1">Top</option>
                                    <option :value="2">Bottoms</option>
                                    <option :value="3">One piece (layerable)</option>
                                    <option :value="4">One piece (no layering)</option>
                                    <option :value="5">Jackets</option>

                                </select>
                                <!-- /.form__control -->

                                <has-error :form="form" :field="'categories.' + category.id + '.layer'"></has-error>

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
                            <loader></loader> Saving categories...
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
            default: function () {
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

        return {

            isLoading: false,
            isSubmitting: false,
            form: new Form(this._extendData({})),
            formData: this._extendData({}),
            categories: [],
            formMessage: null,
            saved: false,
            savedTimeout: null,
        };
    },

    methods: {

        _extendData(data) {

            return _.extend( {

                categories: {},

            }, data );
        },

        submitForm() {

            if (this.isSubmitting) {

                return;
            }

            this.isSubmitting = true;
            this.formMessage = null;

            this.form.fill(this.formData);

            this.form.put(
                options.urls['api.partners.categories.map']
                    .replace('%PARTNER%', this.partner.id)
            ).then((response) => {

                if (response && response.data) {

                    response = response.data;
                }

                if (response.success) {

                    this.saved = true;

                    if (this.savedTimeout) {

                        clearTimeout(this.savedTimeout);
                    }

                    this.savedTimeout = setTimeout(() => {

                        this.saved = false;

                    }, 4000);

                    this.$notify({

                        group: 'global',
                        title: 'Categories saved!',
                        type: 'success'
                    });
                }
            })
                .catch((err) => {

                    if (err && err.response) {

                        let response = err.response;

                        if (response.data) {

                            response = response.data;
                        }

                        if (response.message) {

                            this.formMessage = response.message;
                        }
                    }
                })
                .finally(() => {

                    this.isSubmitting = false;

                });
        },

        retrieveCategories() {

            this.isLoading = true;

            axios.get(
                options.urls['api.partners.categories.list']
                    .replace('%PARTNER%', this.partner.id)
            )
                .then((response) => {

                    if( response ) {

                        if( response.data ) {

                            response = response.data;
                        }

                        if( response.data ) {

                            this.categories = response.data;

                            this.initFormData();
                        }
                    }

                })
            .finally( () => {

                this.isLoading = false;

            })
        },

        initFormData() {

            this.formData.categories = {};

            for ( let category of this.categories ) {

                this.formData.categories[ category.id ] = category;
            }
        }
    },

    mounted() {

        this.retrieveCategories();

    }
}

</script>
