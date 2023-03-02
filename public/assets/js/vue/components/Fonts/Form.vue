"<template>

    <div>

        <alert-error :form="form" :message="formMessage"></alert-error>

        <form class="dashboard__widgets" action="" method="POST" v-on:submit.prevent="submitForm()">

            <div class="dashboard__widget dashboard__widget--6">

                <div class="widget">

                    <header class="widget__header">

                        <h3 class="widget__title">Font Details</h3>
                        <!-- /.widget__title -->

                    </header>
                    <!-- /.widget__header -->

                    <div class="widget__content">


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
                                            v-font="formData.name"
                                            placeholder="Font name..."
                                        >
                                        <!-- /.form__control -->

                                        <div class="help-block" v-if="! form.errors.has( 'name' )">Enter the name of the font</div>
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

                        <div class="row">

                            <div class="col-sm-12">

                                <div class="form__group" :class="{ 'has-error': form.errors.has( 'gender' ) }">

                                    <label class="form__label">Gender</label>
                                    <!-- /.form__label -->

                                    <div class="input__wrapper">

                                        <div class="checkboxes">

                                            <div class="checkbox">

                                                <input
                                                    id="input-gender-female"
                                                    type="radio"
                                                    value="f"
                                                    v-font="formData.gender"
                                                    name="model_gender"
                                                >

                                                <label for="input-gender-female">Female</label>

                                            </div>
                                            <!-- /.checkbox -->
                                            <div class="checkbox">

                                                <input
                                                    id="input-gender-male"
                                                    type="radio"
                                                    value="m"
                                                    v-font="formData.gender"
                                                    name="model_gender"
                                                >

                                                <label for="input-gender-male">Male</label>

                                            </div>
                                            <!-- /.checkbox -->

                                        </div>
                                        <!-- /.checkboxes -->

                                        <div class="help-block" v-if="! form.errors.has( 'gender' )">Select font's gender</div>
                                        <!-- /.help-block -->

                                        <has-error :form="form" field="gender"></has-error>

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

                                <div class="form__group" :class="{ 'has-error': form.errors.has( 'size' ) }">

                                    <label class="form__label">Size</label>
                                    <!-- /.form__label -->

                                    <div class="input__wrapper">

                                        <div class="checkboxes">

                                            <div class="checkbox" v-for="size in sizeChoices">

                                                <input
                                                    :id="'input-size-' + size"
                                                    type="checkbox"
                                                    :value="size"
                                                    v-font="formData.size"
                                                    name="model_size[]"
                                                >

                                                <label :for="'input-size-' + size">{{ size }}</label>

                                            </div>
                                            <!-- /.checkbox -->

                                        </div>
                                        <!-- /.checkboxes -->

                                        <div class="help-block" v-if="! form.errors.has( 'size' )">Select font's size</div>
                                        <!-- /.help-block -->

                                        <has-error :form="form" field="size"></has-error>

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

                                <div class="form__group" :class="{'has-error': form.errors.has( 'front_image' )}">

                                    <label class="form__label">Front Image</label>
                                    <!-- /.form__label -->

                                    <div class="input__wrapper">

                                        <div class="image-uploader">

                                            <file-pond
                                                :allow-multiple="false"
                                                ref="frontImagePond"
                                                allow-drop="true"
                                                style-panel-aspect-ratio="3:5"
                                                style-panel-layout="compact"
                                                imagePreviewTransparencyIndicator="grid"
                                            />

                                        </div>
                                        <!-- /.image-uploader -->

                                        <has-error field="front_image" :form="form"></has-error>

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

                                <div class="form__group" :class="{'has-error': form.errors.has( 'back_image' )}">

                                    <label class="form__label">Back Image</label>
                                    <!-- /.form__label -->

                                    <div class="input__wrapper">

                                        <div class="image-uploader">

                                            <file-pond
                                                :allow-multiple="false"
                                                ref="backImagePond"
                                                allow-drop="true"
                                                style-panel-aspect-ratio="3:5"
                                                style-panel-layout="compact"
                                                imagePreviewTransparencyIndicator="grid"
                                            />

                                        </div>
                                        <!-- /.image-uploader -->

                                        <has-error field="back_image" :form="form"></has-error>

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

                                <div class="form__group" :class="{'has-error': form.errors.has( 'profile_image' )}">

                                    <label class="form__label">Profile Image</label>
                                    <!-- /.form__label -->

                                    <div class="input__wrapper">

                                        <div class="image-uploader">

                                            <file-pond
                                                :allow-multiple="false"
                                                ref="profileImagePond"
                                                allow-drop="true"
                                                style-panel-aspect-ratio="1:1"
                                                style-panel-layout="compact"
                                                imagePreviewTransparencyIndicator="grid"
                                            />

                                        </div>
                                        <!-- /.image-uploader -->

                                        <has-error field="profile_image" :form="form"></has-error>

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

                    </div>
                    <!-- /.widget__content -->

                </div>
                <!-- /.widget -->

            </div>
            <!-- /.dashboard__widget--6 -->

            <!-- /.dashboard__widget dashboard__widget--6 -->

        </form>
        <!-- /.dashboard__widgets -->

    </div>

</template>

<script>

import {Form, HasError, AlertError} from 'vform';

import options from '../../../options';

import Loader from "../Modules/Loader.vue";

import 'filepond/dist/filepond.css';
import vueFilePond from 'vue-filepond';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondImagePreviewPlugin from 'filepond-plugin-image-preview';
const FilePond = vueFilePond( FilePondImagePreviewPlugin );
import {FileOrigin} from 'filepond';

import _ from 'lodash';

export default {

    name: 'font-form',

    props: {

        font: {

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
        Loader,
        FilePond
    },

    data() {

        return {

            isSubmitting: false,
            form: new Form( this._extendData( {} ) ),
            formData: this._extendData( this.font || {} ),
            formMessage: null,
            saved: false,
            savedTimeout: null,
            sizeChoices: [
                'XXXS',
                'XXS',
                'XS',
                'S',
                'M',
                'L',
                'XL',
                'XXL',
                'XXXL'
            ]
        };
    },

    methods: {

        _extendData( data ) {

            return _.extend( {

                name: null,
                gender: 'f',
                size: [],
                dimensions: {
                    height: null,
                    weight: null,
                    neck: null,
                    chest: null,
                    waist: null,
                    hips: null,
                    thighs: null
                },
                front_image: null,
                back_image: null,
                profile_image: null

            }, data );
        },

        submitForm() {

            if( this.isSubmitting ) {

                return;
            }

            this.isSubmitting = true;
            this.formMessage = null;

            // images

            const images = [ 'front', 'back', 'profile' ];

            for( let image of images ) {

                let imageId = null;

                let files = this.$refs[ image + 'ImagePond' ].getFiles();

                if( files.length > 0 ) {

                    imageId = {

                        id: files[0].serverId,
                        name: files[0].filename,
                        uploaded: files[0].origin === FileOrigin.LOCAL
                    };
                }

                this.formData[ image + '_image' ] = imageId;

            }

            // ------------------------------------------------------


            this.form.fill( this.formData );

            let formRequest = null;

            if( ! this.font || ! this.font.id ) {

                formRequest = this.form.post(
                    options.urls[ 'api.fonts.create' ]
                );
            }
            else {

                formRequest = this.form.put(
                    options.urls[ 'api.fonts.edit' ].replace( '%FONT%', this.font.id )
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

                    if( response.font ) {

                        this.formData = this._extendData( response.font );

                        this.processImage( response.font );
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

        initPond() {

            const FilePondNative = require( 'filepond' );

            FilePondNative.setOptions( {

                name: 'file',
                allowReorder: true,
                server: {
                    url: options.urls[ 'filepond' ],
                    process: '/process',
                    revert: '/process',
                    load: '/load/',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }
            } );

            const ponds = $( '.filepond--root' );

            ponds.on( 'FilePond:processfilestart', () => {
                this.isProcessingFiles = true;
            } );

            ponds.on( 'FilePond:processfile', () => {
                this.isProcessingFiles = false;
            } );

            if( this.font ) {

                this.processImages( this.font );
            }
        },

        processImages( font ) {

            const images = [ 'front', 'back', 'profile' ];

            for( let image of images ) {

                if( font[ image + '_image' ] ) {

                    this.$refs[ image + 'ImagePond' ].addFile( font[ image + '_image' ].id, {

                        type: 'local',
                        fileName: font[ image + '_image' ].file_name
                    } );
                }
            }
        }
    },

    watch: {

        'formData.size': function(size) {

            console.log( size );
        }

    },

    mounted() {

        this.initPond();
    }
}

</script>
"
