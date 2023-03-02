<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">Recent</h3>
            <!-- /.widget__title -->

            <loader v-if="isLoading"></loader>

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <div v-if="! isLoading">

                <div v-if="activity_entries && activity_entries.length > 0"></div>

                <div v-else>

                    <div class="empty-text">No data available right now.</div>
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

export default {

    name: 'partners-list',

    props: [],

    components: {

        Loader
    },

    data() {

        return {

            isLoading: false,
            partners: []
        };
    },

    methods: {

        retrievePartners() {

            if (this.isLoading) {

                return;
            }

            this.isLoading = true;

            setTimeout(() => {

                this.isLoading = false;

            }, 1027);

        }
    },

    mounted() {

        this.retrievePartners();

    }

}

</script>
