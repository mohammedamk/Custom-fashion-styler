<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">
                All Models
            </h3>
            <!-- /.widget__title -->

            <loader v-if="isLoading"></loader>

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <div v-if="! isLoading">

                <table v-if="models && models.length > 0" class="table">

                    <thead>

                    <tr>
                        <th width="80%">Model Name</th>
                        <th class="align--right">Actions</th>
                    </tr>

                    </thead>

                    <tbody>

                    <tr v-for="model in models" v-bind:key="model.id">

                        <td width="80%">{{ model.name }}</td>
                        <td class="align--right">

                            <a
                                :href="model.urls.edit"
                                class="btn btn--icon btn--black"
                                v-tooltip="'Edit'"
                            >
                                <EditIcon></EditIcon>
                            </a>
                            <!-- /.text-link -->

                        </td>

                    </tr>

                    </tbody>

                </table>
                <!-- /.table -->

                <div v-else>

                    <div class="empty-text">

                        No model has been added yet.
                        <br>
                        <br>
                        <a :href="getUrl( 'dashboard.models.new' )" class="btn">Add first model</a>
                        <!-- /.btn -->

                    </div>
                    <!-- /.empty-text -->

                </div>

            </div>

        </div>
        <!-- /.widget__content -->

    </div>
    <!-- /.widget -->

</template>

<script>

import Loader from '../Modules/Loader.vue';

import axios from 'axios';

import options from '../../../options';

import EditIcon from '../../../../images/svg/icon_edit.svg?inline';

export default {

    name: 'models-list',

    props: [],

    inject: [
        'getUrl'
    ],

    components: {

        Loader,
        EditIcon
    },

    data() {

        return {

            isLoading: false,
            models: []
        };
    },

    methods: {

        retrieveModels() {

            if (this.isLoading) {

                return;
            }

            this.isLoading = true;

            axios.get(options.urls['api.models.list'])
                .then((response) => {

                    if (response) {

                        if (response.data) {

                            response = response.data;
                        }

                        if( response.data ) {

                            this.models = response.data;
                        }
                    }
                })
            .finally( () => {

                this.isLoading = false;

            } );
        }
    },

    mounted() {

        this.retrieveModels();

    }

}

</script>
