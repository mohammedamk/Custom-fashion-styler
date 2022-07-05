<template>

    <div>

        <article
            v-on:click="openModal()"
            class="moda-match-item-card"
            :class="{'moda-match-item-card--is-added': hasFittingRoomItem(item)}">

            <div class="moda-match-item-card-image">

                <span class="moda-match-item-card-badge" v-on:click.stop="addToFittingRoom( 'hanger icon' )">
                    <PlusIcon v-if="!hasFittingRoomItem(item)"></PlusIcon>
                    <CheckIcon v-if="hasFittingRoomItem(item)"></CheckIcon>
                </span>
                <!-- /.moda-match-item-card-badge -->

                <figure v-if="item.image" v-html="item.image.thumb"></figure>

            </div>
            <!-- /.moda-match-item-card-image -->

            <header class="moda-match-item-card-header">

                <h3 class="moda-match-item-card-title">
                    <a href="#">{{itemData.title}}</a>
                </h3>

                <div class="moda-match-item-card-price">
                    {{formatPrice( itemData.price )}}
                </div>

            </header>
            <!-- /.moda-match-item-card-header -->

        </article>
        <!-- /.moda-match-item-card -->

        <modal :title="itemData.title" ref="modal">

          <!-- <figure v-if="item.image" class="moda-match-modal-image" v-html="item.image.thumb"></figure> -->
          <div class="moda-match-modal-image-container">
            <ssr-carousel
              class="moda-match-modal-image"
              :class="{ 'moda-match-modal-image-full': !item.gallery.length }"
              show-arrows
              :slides-per-page='1'
              :paginate-by-slide='true'
              ref="carousel"
              v-on:change="onSlideChange"
            >

              <template v-if="!item.gallery.length">
                <figure v-if="item.image" v-html="item.image.thumb" :index='0' v-bind:key="item.image.id"></figure>
              </template>
              <template v-else>
                <figure v-for="(gallery_image, index) in item.gallery" v-html="gallery_image.thumb" :index="index" v-bind:key="gallery_image.id"></figure>
              </template>

              <template #back-arrow='{ disabled }'>
                <svg class="image-gallery-svg" xmlns="http://www.w3.org/2000/svg" viewBox="6 0 12 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
              </template>
              <template #next-arrow='{ disabled }'>
                <svg class="image-gallery-svg" xmlns="http://www.w3.org/2000/svg" viewBox="6 0 12 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </template>
            </ssr-carousel>

            <div class="moda-match-modal-image-gallery" ref="gallery" v-if="item.gallery.length">
              <figure v-for="(gallery_image, index) in item.gallery" v-bind:key="gallery_image.id" v-html="gallery_image.thumb" v-on:click.prevent="slideTo(index)" :class="{ 'moda-match-modal-image-gallery-active' : (index == active_index) }"></figure>
            </div>
          </div>
          <!-- /.moda-match-modal-image -->

            <div class="moda-match-modal-price">{{formatPrice( itemData.price )}}</div>
            <!-- /.moda-match-modal-price -->

            <button
                class="moda-match-btn moda-match-btn--black moda-match-btn--block moda-match-btn--size-medium"
                :class="{'moda-match-btn--secondary': hasFittingRoomItem( item )}"
                type="button"
                v-on:click.prevent="addToFittingRoom()"
            >
                {{ ! hasFittingRoomItem( item ) ? 'Add to fitting room' : 'Remove from fitting room' }}
            </button>
            <!-- /.moda-match-btn modal-match-btn--block -->

            <div class="moda-match-modal-text" v-html="itemData.description"></div>
            <!-- /.moda-match-modal-text -->

        </modal>

    </div>

</template>

<script>

import $ from 'jquery';

import FittingRoomIcon from '../../../images/svg/checkroom_black_24dp.svg?inline';
import PlusIcon from '../../../images/svg/plus.svg?inline';
import CheckIcon from '../../../images/svg/check.svg?inline';

import Modal from './Modal.vue';

import SsrCarousel from 'vue-ssr-carousel';
import ssrCarouselCss from 'vue-ssr-carousel/index.css';

export default {

    name: 'item-card',

    props: [

        'item'
    ],

    components: {
        PlusIcon,
        CheckIcon,
        FittingRoomIcon,
        Modal,
        SsrCarousel,
        ssrCarouselCss
    },

    data() {

        return {

            itemData: this._extendData( this.item ),
            isModalOpen: false,
            active_index: 0
        };
    },

    inject: [

        'formatPrice',
        'addFittingRoomItem',
        'hasFittingRoomItem',
        'removeFittingRoomItem',
        'track'
    ],

    methods: {

        _extendData( data ) {

            return $.extend( {

                id: null,
                title: '',
                image: 'https://via.placeholder.com/280x420/e8e8e8/bb86fc',
                price: ''

            }, data );
        },

        addToFittingRoom( trigger ) {

            if( typeof window.navigator.vibrate !== 'undefined' ) {

                window.navigator.vibrate(100);
            }

            if( ! this.hasFittingRoomItem( this.item ) ) {

                this.track( "Product Added Button", 'Product', 'Clicked', trigger );

                this.addFittingRoomItem( this.item );
            }
            else {

                this.track( "Product Removed Button", 'Product', 'Clicked', trigger );

                this.removeFittingRoomItem( this.item );
            }

            this.closeModal();

        },

        openModal() {
          let this_item = this.item;
          this.track( 'Product Viewed', 'Product', 'Viewed', this.item.title );
          this.$refs.modal.open();
        },

        closeModal() {
          this.$refs.modal.close();
        },

        slideTo(the_index) {
          this.active_index = the_index;
          this.$refs.carousel.goto(the_index);
          this.scrollGallery(the_index);
        },

        onSlideChange({ index }) {
          this.active_index = index;
          this.scrollGallery(index);
        },

        scrollGallery(index) {
          this.$refs.gallery.scrollTop = 147 * index;
        }
    }

}

</script>
