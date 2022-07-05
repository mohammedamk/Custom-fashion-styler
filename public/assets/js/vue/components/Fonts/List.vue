<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">
                All Fonts
            </h3>
            <!-- /.widget__title -->

            <loader v-if="isLoading"></loader>

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <div v-if="! isLoading">

                <table v-if="fonts && fonts.length > 0" class="table">

                    <thead>

                    <tr>
                        <th width="80%">Model Name</th>
                        <th class="align--right">Actions</th>
                    </tr>

                    </thead>

                    <tbody>

                    <tr v-for="font in fonts" v-bind:key="font.id">

                        <td width="80%">{{ font.name }}</td>
                        <td class="align--right">

                            <a
                                :href="font.urls.edit"
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

                        No font has been added yet.
                        <br>
                        <br>
                        <a :href="getUrl( 'dashboard.fonts.new' )" class="btn">Add first font</a>
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

    name: 'fonts-list',

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
            fonts: []
        };
    },

    methods: {

        retrieveModels() {

            if (this.isLoading) {

                return;
            }

            this.isLoading = true;

            axios.get(options.urls['api.fonts.list'])
                .then((response) => {

                    if (response) {

                        if (response.data) {

                            response = response.data;
                        }

                        if( response.data ) {

                            this.fonts = response.data;
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
