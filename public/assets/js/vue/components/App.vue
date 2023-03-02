<template>

    <div id="app">

        <slot></slot>

        <notifications group="global" />

    </div>
    <!-- /#app -->

</template>

<script>

import App from '../../app';

import parseISO from "date-fns/parseISO";
import {utcToZonedTime} from "date-fns-tz";
import format from "date-fns/format";

export default {

    name: 'app',

    props: [],

    data() {
        return {
        };
    },

    methods: {

        getUrl( name ) {

            let url = null;

            if( typeof App.options.urls[ name ] !== 'undefined' ) {

                url = App.options.urls[ name ];
            }

            return url;
        },

        timezoned( date, timezone ) {
            return utcToZonedTime( date, timezone );
        },
        formatDate( date, timezone ) {
            date = parseISO( date );
            if( timezone ) {
                date = this.timezoned( date, timezone );
            }
            return format( date, 'LLL d yyyy' )
        },
        formatTime( date, timezone, f ) {
            date = parseISO( date );
            if( timezone ) {
                date = this.timezoned( date, timezone );
            }
            if( ! f ) {
                f = 'h:mm a';
            }
            return format( date, f );
        },
    },

    provide() {

        return {

            getUrl: this.getUrl,
            formatDate: this.formatDate,
            formatTime: this.formatTime,
            timezoned: this.timezoned,
        };
    }

};


</script>
