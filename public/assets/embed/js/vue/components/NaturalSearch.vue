<template>

    <form method="POST" v-on:submit.prevent="getResults()" class="moda-match-natural-search" :class="{'moda-match-natural-search--active': isDisplayed}">

        <button class="moda-match-natural-search-close" v-on:click="close()" type="button">
            <close-icon></close-icon>
        </button>
        <!-- /.moda-match-natural-search-close -->

        <div class="moda-match-natural-search-text" v-if="!noResults">

            I am looking for a model that weighs about

            <div class="moda-match-natural-search-keep-together">

                <input type="number" step="1" v-model="searchOptions.weight">

                <select v-model="searchOptions.weightUnit">
                    <option value="lb">lb</option>
                    <option value="kg">kg</option>
                </select>
                <!-- /# -->

            </div>

            <br><br>

            and whose height is about

            <div class="moda-match-natural-search-keep-together">

                <input v-if="searchOptions.heightUnit !== 'ft'" type="number" step="1" v-model="searchOptions.height">

                <div v-else class="moda-match-natural-search-input-group">

                  <select v-model="searchOptions.height"><option :value="null"></option><option :value="feet" v-for="feet in feetChoices">{{feet}}</option></select>&nbsp;'&nbsp;<select v-model="searchOptions.height_extended"><option :value="null"></option><option :value="inches" v-for="inches in inchesChoices">{{inches}}</option></select>&nbsp;"</div>&nbsp;<select v-model="searchOptions.heightUnit"><option value="cm">cm</option><option value="ft">ft</option></select>.

            </div>

        </div>
        <!-- /.moda-match-natural-search-text -->

        <div v-else class="moda-match-natural-search-text">

            We couldn't find a model matching those criteria.

        </div>

        <footer class="moda-match-natural-search-footer">

            <button
              v-if="!noResults"
              class="moda-match-btn moda-match-btn--secondary moda-match-btn--block"
              type="submit"
            >
              Find models
            </button>
            <!-- /.moda-match-btn moda-match-btn--secondary moda-match-btn--block -->

            <button
              v-else
              v-on:click.prevent="resetSearch()"
              class="moda-match-btn moda-match-btn--secondary moda-match-btn--block"
              type="button"
            >
              Try again
            </button>
            <!-- /.moda-match-btn moda-match-btn--secondary moda-match-btn--block -->

            <a href="#" class="moda-match-natural-search-link" v-on:click.prevent="close( true )">Show All Models</a>
            <!-- /.moda-match-natural-search-link -->

        </footer>
        <!-- /.moda-match-natural-search-footer -->

    </form>

</template>

<script>

import CloseIcon from '../../../images/svg/close-icon.svg?inline';

import _ from 'lodash';

export default {

    name: 'natural-search',

    components: {

        CloseIcon
    },

    inject: [
        'preventAppScroll',
        'track'
    ],

    data() {

        let inchesChoices = [];

        for( let i = 0; i < 12; i++ ) {

            inchesChoices.push( i );
        }

        let feetChoices = [];

        for( let i = 4; i < 9; i++ ) {

            feetChoices.push( i );
        }

        return {

            searchOptions: this._extendSearchOptions({}),
            isDisplayed: false,
            models: [],
            cb: function( models ) {},
            noResults: false,
            inchesChoices: inchesChoices,
            feetChoices: feetChoices
        };
    },

    methods: {

        _extendSearchOptions(data) {

            return _.extend(data, {

                weight: null,
                weightUnit: 'lb',
                height: null,
                height_extended: 0,
                heightUnit: 'ft'

            });
        },

        getWeightInLb(weightToMatch){
            return weightToMatch * 2.20462;
        },

        getheightInCm(heightUnit, height_extended){
            return heightUnit * 30.48 +  height_extended * 2.54;
        },


        getResults() {

            let weightToMatch = parseFloat(this.searchOptions.weight);

            if (this.searchOptions.weightUnit !== 'lb') {

                weightToMatch = this.getWeightInLb(weightToMatch);
            }

            let heightToMatch = parseFloat(this.searchOptions.height);

            if (this.searchOptions.heightUnit === 'ft') {

                heightToMatch = this.getheightInCm(heightToMatch, parseFloat( this.searchOptions.height_extended ));
            }

            let filteredModels = this.models;

            if (weightToMatch) {

                filteredModels = _.filter( filteredModels , (m) => {

                    if (!m.dimensions) {

                        return false;
                    }

                    if (!m.dimensions.weight) {

                        return false;
                    }

                    const weight = parseFloat( m.dimensions.weight );

                    const weightDiff = 25;
                    const weightMin = weight - weightDiff;
                    const weightMax = weight + weightDiff;

                    return weightToMatch >= weightMin && weightToMatch <= weightMax;
                } );
            }

            if( heightToMatch ) {

                filteredModels = _.filter( filteredModels , (m) => {

                    if (!m.dimensions) {

                        return false;
                    }

                    if (!m.dimensions.height) {

                        return false;
                    }

                    const height = parseInt( m.dimensions.height );

                    const heightDiff = this.getheightInCm(0, 5);
                    const heightMin = height - heightDiff;
                    const heightMax = height + heightDiff;

                    return heightToMatch >= heightMin && heightToMatch <= heightMax;
                } );
            }

            this.track( 'Natural Search Results', 'Natural Search', 'Results', null, filteredModels.length > 0 ? 1 : 0 );

            if( filteredModels.length < 1 ) {
              console.log( 'noResults' );
              this.close();
                // this.noResults = true;
                this.$notify( {

                    group: 'global',
                    title: 'We don’t currently have a model matching that criteria. Please choose from the list below.',
                    type: 'error'

                } );
                return;
            }

            if( typeof this.cb === 'function' ) {

                this.cb( filteredModels );

                this.close();
            }
        },

        close( reset ) {

            this.isDisplayed = false;

            if( reset && typeof this.cb === 'function' ) {

                this.cb( [] );
            }
        },

        open( models, cb, trigger ) {

            this.models = models;

            this.isDisplayed = true;

            if( trigger ) {

                this.track( 'Natural Search Button', 'Natural Search', 'Clicked', trigger );
            }

            this.cb = cb;
        },

        resetSearch() {

            this.noResults = false;
        }
    },

    watch: {

        isDisplayed: function () {

            if (this.isDisplayed === false) {

                this.searchOptions = this._extendSearchOptions({});

                this.preventAppScroll(false);
            } else {

                this.preventAppScroll(true);
            }
        }
    }
}

</script>
