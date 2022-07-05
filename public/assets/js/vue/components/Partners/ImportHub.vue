<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">
                Import Products
            </h3>
            <!-- /.widget__title -->

            <loader v-if="isImporting"></loader>

        </header>
        <!-- /.widget__header -->

        <div class="padding--v-80 align--center">

            <div v-if="! importJobId">

                <button
                    class="btn"
                    type="button"
                    :disabled="isImporting"
                    v-on:click="startImport()"
                >Import</button>
                <!-- /.btn -->

                <div class="checkbox margin--t-30">

                    <input
                        id="input-from-scratc"
                        type="checkbox"
                        value="1"
                        v-model="formData.from_scratch"
                    >

                    <label for="input-from-scratc">Clear current products</label>

                </div>
                <!-- /.checkbox -->

            </div>

            <div v-else>

                <progress-bar :val="progress" :text="status"></progress-bar>

            </div>

        </div>
        <!-- /.boxed boxed--740 -->


    </div>
    <!-- /.widget -->

</template>

<script>

import axios from 'axios';

import options from '../../../options';

import ProgressBar from 'vue-simple-progress';
import Loader from '../Modules/Loader.vue';

export default {

    name: 'partner-import-hub',

    props: [

        'partner',
        'refresh'
    ],

    components: {

        ProgressBar,
        Loader
    },

    data() {

        return {

            isImporting: false,
            importJobId: null,
            status: 'Idle',
            progress: 0,
            timeout: null,
            formData: {

                fromScratch: 0
            }
        };
    },

    methods: {

        startImport() {

            if( this.isImporting ) {

                return;
            }

            this.isImporting = true;

            axios.post(
                options.urls[ 'api.partners.import' ]
                    .replace( '%PARTNER%', this.partner.id ),
                {
                    from_scratch: this.formData.from_scratch
                }
            )
            .then( ( response ) => {

                if( response ) {

                    if( response.data ) {

                        response = response.data;
                    }

                    if( response.success && response.job_id ) {

                        this.status = 'Waiting for queue';
                        this.importJobId = response.job_id;
                    }
                }

            } )
            .finally( () => {

                if( ! this.importJobId ) {

                    this.isImporting = false;
                }
            } );
        },

        setupTimeout() {

            if( this.timeout ) {

                clearTimeout( this.timeout );
            }

            this.timeout = setInterval( () => {

                this.retrieveStatus();

            }, 1000 );
        },

        retrieveStatus() {


            if( ! this.importJobId ) {

                return;
            }

            axios.get(
                options.urls[ 'api.partners.import.status' ]
                    .replace( '%PARTNER%', this.partner.id )
                    .replace( '%JOB_ID%', this.importJobId )
            )
            .then( ( response ) => {

                if( response ) {

                    if( response.data ) {

                        response = response.data;
                    }

                    this.progress = response.progress;

                    if( response.message ) {

                        this.status = response.message;
                    }

                    if( response.is_ended ) {

                        if( response.progress === 100 ) {

                            this.$notify( {

                                group: 'global',
                                title: 'Import completed!',
                                type: 'success'

                            } );

                            if( this.refresh ) {

                                this.$notify( {

                                    group: 'global',
                                    title: 'Refreshing...',
                                    type: 'info'

                                } );

                                document.location = document.location.href;
                            }
                        }

                        this.importJobId = null;
                        this.isImporting = false;
                        this.progress = 0;
                        this.status = '';
                    }
                }

            } );
        }
    },

    watch: {

        importJobId: function( id ) {

            if( id ) {

                this.retrieveStatus();
                this.setupTimeout();
            }
            else {

                if( this.timeout ) {

                    clearTimeout( this.timeout );
                }
            }
        }
    }

};

</script>
