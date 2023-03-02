<template>

    <header class="moda-match-header">

        <div class="moda-match-header-wrapper">

            <a
                href="#"
                class="moda-match-header-toggle"
                v-on:click.prevent="closeEmbedModal()"
            >
                <close-icon></close-icon>
            </a>
            <!-- /.moda-match-header-toggle -->

            <h1 class="moda-match-header-title">
                <slot></slot>
            </h1>
            <!-- /.moda-match-header-title -->

            <div class="moda-match-header-logo">

                <logo></logo>

            </div>
            <!-- /.moda-match-header-logo -->

        </div>
        <!-- /.moda-match-header-wrapper -->

    </header>

</template>

<script>

import Logo from '../../../images/svg/moda-match-logo-svg.svg?inline';

import CloseIcon from '../../../images/svg/close-icon.svg?inline';


export default {

    name: 'app-header',

    components: {

        Logo,
        CloseIcon
    },

    data() {

        return {

        };
    },

    inject: [

        'getAsset',
        'getEmbed'
    ],

    methods: {

        closeEmbedModal() {

            const embed = this.getEmbed();

            embed.closeModal();

        }
    }
};

</script>
