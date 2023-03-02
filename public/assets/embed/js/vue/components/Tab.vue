<template>

    <div class="moda-match-tabs-panel" v-if="isActive" :class="{'moda-match-tabs-panel--active': isActive}">

        <div class="moda-match-app-content">

            <slot></slot>

        </div>
        <!-- /.moda-match-app-content -->

    </div>

</template>

<script>

export default {

    name: 'tab',

    props: [

        'title'
    ],

    inject: [ 'registerTab' ],

    data() {

        return {

            isActive: false
        };
    },

    mounted() {

        this.registerTab( this );
    }

};

</script>