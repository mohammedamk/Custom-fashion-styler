<template>

    <div class="moda-match-modal" v-if="isOpen">

        <div class="moda-match-modal-viewport" v-on:click.stop="close()">

            <div class="moda-match-modal-inner" v-on:click.stop>

                <header class="moda-match-modal-header">

                    <h3 class="moda-match-modal-title">{{ dynamicTitle }}</h3>
                    <!-- /.moda-match-modal-title -->

                    <a class="moda-match-modal-close" href="#" v-on:click.prevent="close()">
                        <close-icon></close-icon>
                    </a>
                    <!-- /.moda-match-modal-close -->

                </header>
                <!-- /.moda-match-modal-header -->

                <slot></slot>

            </div>
            <!-- /.moda-match-modal-inner -->

        </div>
        <!-- /.moda-match-modal-viewport -->

    </div>
    <!-- /.moda-match-modal -->

</template>

<script>

import CloseIcon from '../../../images/svg/close-icon.svg?inline';

export default {

    name: 'modal',

    components: {

        CloseIcon
    },

    inject: [

        'registerModal',
        'closeActiveModals'
    ],

    props: [

        'title'
    ],

    data() {

        return {

            isOpen: false,
            dynamicTitle: this.title,
        };
    },

    methods: {

        open() {

            this.closeActiveModals();

            this.isOpen = true;
        },

        close() {

            this.isOpen = false;
        },

        setTitle( title ) {

            this.dynamicTitle = title;
        }
    },

    mounted() {

        this.registerModal( this );
        if(document.getElementById("moda-match-app")){
            document.getElementById("moda-match-app").appendChild(this.$el);
        }
    }

};

</script>
