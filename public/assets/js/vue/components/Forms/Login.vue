<template>

    <div>

        <form action="" method="POST" v-on:submit.prevent="submitForm()" novalidate>

            <alert-error class="margin--t-46" :form="form"></alert-error>

            <div class="form__group margin--t-46"
                 :class="{ 'has-error': form.errors.has( 'email' ) }">

                <div class="input__wrapper">

                    <input
                        id="input-email"
                        required
                        type="email"
                        class="form__control"
                        v-model="formData.email"
                        placeholder="Email address"
                    >
                    <!-- /.form__control -->

                    <has-error :form="form" field="email"></has-error>

                </div>
                <!-- /.input__wrapper -->

            </div>
            <!-- /.form__group margin--t-46 -->

            <div class="form__group"
                 :class="{ 'has-error': form.errors.has( 'password' ) }">

                <div class="input__wrapper">

                    <input
                        id="input-password"
                        required
                        type="password"
                        class="form__control"
                        v-model="formData.password"
                        placeholder="Password"
                    >
                    <!-- /.form__control -->

                    <has-error :form="form" field="password"></has-error>

                </div>
                <!-- /.input__wrapper -->

            </div>
            <!-- /.form__group -->

            <div class="form__links">

                <div class="form__links--left"></div>
                <!-- /.form__links--left -->

                <div class="form__links--right">

                    <a :href="getUrl( 'password.request' )">Forgot password</a>

                </div>
                <!-- /.form__links--right -->

            </div>
            <!-- /.form__links -->

            <footer class="form__footer">

                <div>

                    <button class="btn btn--block btn--dark btn--color-white" type="submit" :disabled="isLoading">
                        {{! isLoading ? 'Login' : 'Please wait...'}}
                    </button>
                    <!-- /.btn btn--block -->

                </div>

            </footer>
            <!-- /.form__footer -->

        </form>

    </div>

</template>

<script>

import options from '../../../options';

import { Form, HasError, AlertSuccess, AlertError } from 'vform';

import axios from 'axios';

export default {

    name: 'signin-form',

    components: {

        HasError,
        AlertSuccess,
        AlertError
    },

    inject: [

        'getUrl'
    ],

    data() {

        return {

            isLoading: false,
            form: new Form( {

                email: '',
                password: ''
            } ),
            formData: {

                email: null,
                password: null
            }
        };
    },

    methods: {

        submitForm() {

            if( this.isLoading ) {

                return;
            }

            this.isLoading = true;

            this.form.fill( this.formData );

            axios.get('/sanctum/csrf-cookie').then(response => {

                this.form.post(
                    options.urls[ 'login.process' ]
                )
                    .then( ( response ) => {

                        if( response && response.data ) {

                            response = response.data;
                        }

                        if( response.redirect ) {

                            document.location = response.redirect;
                        }
                    } )
                    .finally( () => {

                        this.isLoading = false;

                    } );
            });
        }
    }

};

</script>
