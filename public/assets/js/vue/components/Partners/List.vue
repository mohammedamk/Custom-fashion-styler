<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">
                All Partners
            </h3>
            <!-- /.widget__title -->

            <loader v-if="isLoading"></loader>

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <nav class="tabs-toggle">

                <a
                    href="#"
                    v-on:click.prevent="currentList = 'all'"
                    :class="{ 'tabs-toggle--is-active': currentList === 'all' }">All</a>
                <a
                    href="#"
                    v-on:click.prevent="currentList = 'approved'"
                    :class="{ 'tabs-toggle--is-active': currentList === 'approved' }">Approved</a>

                <a
                    href="#"
                    v-on:click.prevent="currentList = 'pending'"
                   :class="{ 'tabs-toggle--is-active': currentList === 'pending' }">Pending</a>

            </nav>
            <!-- /.tabs-toggle -->

            <div v-if="! isLoading">

                <table v-if="partners && partners.length > 0" class="table">

                    <thead>

                    <tr>
                        <th>Partner Name</th>
                        <th>Views</th>
                        <th>Sales</th>
                        <th>Conversion</th>
                        <th>Status</th>
                        <th class="align--right">Actions</th>
                    </tr>

                    </thead>

                    <tbody>

                    <tr v-for="partner in partners" v-bind:key="partner.id">

                        <td>{{ partner.name }}</td>
                        <td>{{ partner.views }}</td>
                        <td>{{ partner.sales }}</td>
                        <td>{{ partner.conversion }}</td>
                        <td>
                            <span class="circle" v-tooltip="! partner.is_pending ? 'Approved' : 'Pending'" :class="{ 'circle--green': ! partner.is_pending }"></span>
                            <!-- /.circle -->
                        </td>
                        <td class="align--right">

                            <a
                                :href="partner.urls.edit"
                                class="btn btn--icon btn--black"
                                v-tooltip="'Edit'"
                            >
                                <EditIcon></EditIcon>
                            </a>
                            <!-- /.text-link -->

                            <a
                                :href="! partner.is_pending ? partner.urls.products : '#'"
                                class="btn btn--icon btn--blue"
                                :class="{ 'btn--disabled': partner.is_pending }"
                                v-tooltip="! partner.is_pending ? 'Products' : ''"
                            >
                                <ProductIcon></ProductIcon>
                            </a>
                            <!-- /.text-link -->

                        </td>

                    </tr>

                    </tbody>

                </table>
                <!-- /.table -->

                <div v-else>

                    <div class="empty-text">

                        No partner has been added yet.
                        <br>
                        <br>
                        <a :href="getUrl( 'dashboard.partners.new' )" class="btn">Add first partner</a>
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
import ProductIcon from '../../../../images/svg/icon_products.svg?inline';

export default {

    name: 'partners-list',

    props: [],

    inject: [
        'getUrl'
    ],

    components: {

        Loader,
        EditIcon,
        ProductIcon
    },

    data() {

        return {

            isLoading: false,
            currentList: 'approved',
            partners: []
        };
    },

    methods: {

        retrievePartners( force ) {

            if ( this.isLoading && ! force ) {

                return;
            }

            this.isLoading = true;

            axios.get( options.urls['api.partners.list'], {

                params: {

                    list: this.currentList

                }
            } )
                .then((response) => {

                    if (response) {

                        if (response.data) {

                            response = response.data;
                        }

                        if( response.data ) {

                            this.partners = response.data;
                        }
                    }
                })
            .finally( () => {

                this.isLoading = false;

            } );
        }
    },

    watch: {

        currentList: function() {

            this.retrievePartners( true );

        }
    },

    mounted() {

        this.retrievePartners();

    }

}

</script>
