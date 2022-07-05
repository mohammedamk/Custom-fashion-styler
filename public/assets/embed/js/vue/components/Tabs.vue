<template>

    <div class="moda-match-tabs">

        <nav class="moda-match-tabs-nav">

            <a
                v-for="tab in tabs"
                href="#" class="moda-match-tabs-nav-item"
                v-on:click.prevent="openTab( tab )"
                :class="{'moda-match-tabs-nav-item--active': activeTab === tab}"
            >
                {{tab.title}}
            </a>

        </nav>
        <!-- /.moda-match-tabs-nav -->

        <div class="moda-match-tabs-panels">

            <slot></slot>

        </div>
        <!-- /.moda-match-tabs-panels -->

    </div>
    <!-- /.moda-match-tabs -->

</template>

<script>

export default {

    name: 'tabs',

    data() {

        return {

            tabs: [],
            activeTab: null
        };
    },

    methods: {

        registerTab( tab ) {

            this.tabs.push( tab );

            if( ! this.activeTab ) {

                this.activeTab = tab;
            }
        },

        openTab( tab ) {

            if( this.activeTab ) {

                if( this.activeTab === tab ) {

                    return;
                }
            }

            this.activeTab = tab;
        }
    },

    watch: {

        activeTab: function( current, prev ) {

            if( prev ) {

                prev.isActive = false;
            }

            if( current ) {

                current.isActive = true;
            }
        }
    },

    provide() {

        return {

            registerTab: this.registerTab
        };
    }

}

</script>