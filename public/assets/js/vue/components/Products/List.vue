<template>

    <div class="widget">

        <header class="widget__header">

            <h3 class="widget__title">
                Products
            </h3>
            <!-- /.widget__title -->

            <loader v-if="isLoading"></loader>

        </header>
        <!-- /.widget__header -->

        <div class="widget__content">

            <div v-if="! isLoading">

                <div v-if="products && products.length > 0">

                    <div class="table-scroll">

                        <table class="table">

                            <thead>

                            <tr class="table--no-bg">
                                <th colspan="2"></th>
                                <th colspan="2"></th>
                                <th :style="{'min-width': partner.is_rotate_enabled ? '220px' : '90px'}" v-for="model in models" v-bind:key="model.id" class="align--center">
                                    <figure v-if="model.profile_image" style="width: 80px" class="table-image" v-html="model.profile_image.thumb"></figure>
                                    <!-- /.table-image -->
                                </th>
                            </tr>

                            <tr>
                                <th style="min-width: 120px"></th>
                                <th style="min-width: 212px">Product Name</th>
                                <th>Price</th>
                                <th style="min-width: 306px;">Gallery</th>
                                <th :style="{'min-width': partner.is_rotate_enabled ? '220px' : '90px'}" v-for="model in models" v-bind:key="model.id">
                                    {{model.name}}
                                </th>
                            </tr>

                            </thead>

                            <tbody v-for="product in products" v-bind:key="product.id">

                            <tr v-if="! product.is_variant">

                                <td style="min-width: 120px;">
                                    <figure class="table-image" v-if="product.image" v-html="product.image.thumb"></figure>
                                    <!-- /.table-image -->
                                </td>
                                <td>{{ product.title_with_options }}</td>
                                <td>{{ product.price }}</td>
                                <td>
                                  <div class="table-uploaders">
                                    <div class="table-uploader">
                                      <file-pond
                                          label-idle="Drop Front Gallery Image"
                                          :allow-multiple="false"
                                          :ref="'product' + product.id + 'FrontGalleryImage'"
                                          allow-drop="true"
                                          style-panel-aspect-ratio="1:1"
                                          style-panel-layout="compact"
                                          imagePreviewTransparencyIndicator="grid"
                                          v-bind:files="imagesPool[ product.id ][ product.source_product_id ].front"
                                          @processfile="(err, file) => processGalleryImage( file, product, 'front' )"
                                          @removefile="(err, file) => removeGalleryImage( product, 'front' )"
                                      />
                                    </div>
                                    <div class="table-uploader">
                                      <file-pond
                                          label-idle="Drop Back Gallery Image"
                                          :allow-multiple="false"
                                          :ref="'product' + product.id + 'BackGalleryImage'"
                                          allow-drop="true"
                                          style-panel-aspect-ratio="1:1"
                                          style-panel-layout="compact"
                                          imagePreviewTransparencyIndicator="grid"
                                          v-bind:files="imagesPool[ product.id ][ product.source_product_id ].back"
                                          @processfile="(err, file) => processGalleryImage( file, product, 'back' )"
                                          @removefile="(err, file) => removeGalleryImage( product, 'back' )"
                                      />
                                    </div>
                                    <div class="table-uploader">
                                      <file-pond
                                          label-idle="Drop Side Gallery Image"
                                          :allow-multiple="false"
                                          :ref="'product' + product.id + 'SideGalleryImage'"
                                          allow-drop="true"
                                          style-panel-aspect-ratio="1:1"
                                          style-panel-layout="compact"
                                          imagePreviewTransparencyIndicator="grid"
                                          v-bind:files="imagesPool[ product.id ][ product.source_product_id ].side"
                                          @processfile="(err, file) => processGalleryImage( file, product, 'side' )"
                                          @removefile="(err, file) => removeGalleryImage( product, 'side' )"
                                      />
                                    </div>
                                  </div>
                                </td>
                                <td v-for="model in models" v-bind:key="model.id">

                                    <div class="table-uploaders">

                                        <div class="table-uploader">

                                            <file-pond
                                                label-idle="Drop Front Image"
                                                :allow-multiple="false"
                                                :ref="'product' + product.id + 'Model' + model.id + 'FrontImage'"
                                                allow-drop="true"
                                                style-panel-aspect-ratio="1:1"
                                                style-panel-layout="compact"
                                                imagePreviewTransparencyIndicator="grid"
                                                v-bind:files="imagesPool[ product.id ][ model.id ].front"
                                                @processfile="(err, file) => processImage( file, product, 'front', model.id )"
                                                @removefile="(err, file) => removeImage( product, 'front', model.id )"
                                            />

                                        </div>
                                        <!-- /.table-uploader -->

                                        <div class="table-uploader" v-if="partner.is_rotate_enabled">

                                            <file-pond
                                                label-idle="Drop Back Image"
                                                :allow-multiple="false"
                                                :ref="'product' + product.id + 'Model' + model.id + 'BackImage'"
                                                allow-drop="true"
                                                style-panel-aspect-ratio="1:1"
                                                style-panel-layout="compact"
                                                imagePreviewTransparencyIndicator="grid"
                                                v-bind:files="imagesPool[ product.id ][ model.id ].back"
                                                @processfile="(err, file) => processImage( file, product, 'back', model.id )"
                                                @removefile="(err, file) => removeImage( product, 'back', model.id )"
                                            />

                                        </div>
                                        <!-- /.table-uploader -->

                                    </div>
                                    <!-- /.table-uploaders -->

                                </td>
                            </tr>

                            <tr v-else>

                                <td style="min-width: 120px; padding-left: 40px;">
                                    <figure class="table-image" v-if="product.image" v-html="product.image.thumb"></figure>
                                    <!-- /.table-image -->
                                </td>
                                <td>{{ product.title_with_options }}</td>
                                <td>{{ product.price }}</td>
                                <td>
                                  <div class="table-uploaders">
                                    <div class="table-uploader">
                                      <file-pond
                                          label-idle="Drop Front Gallery Image"
                                          :allow-multiple="false"
                                          :ref="'product' + product.id + 'FrontGalleryImage'"
                                          allow-drop="true"
                                          style-panel-aspect-ratio="1:1"
                                          style-panel-layout="compact"
                                          imagePreviewTransparencyIndicator="grid"
                                          v-bind:files="imagesPool[ product.id ][ product.source_product_id ].front"
                                          @processfile="(err, file) => processGalleryImage( file, product, 'front' )"
                                          @removefile="(err, file) => removeGalleryImage( product, 'front' )"
                                      />
                                    </div>
                                    <div class="table-uploader">
                                      <file-pond
                                          label-idle="Drop Back Gallery Image"
                                          :allow-multiple="false"
                                          :ref="'product' + product.id + 'BackGalleryImage'"
                                          allow-drop="true"
                                          style-panel-aspect-ratio="1:1"
                                          style-panel-layout="compact"
                                          imagePreviewTransparencyIndicator="grid"
                                          v-bind:files="imagesPool[ product.id ][ product.source_product_id ].back"
                                          @processfile="(err, file) => processGalleryImage( file, product, 'back' )"
                                          @removefile="(err, file) => removeGalleryImage( product, 'back' )"
                                      />
                                    </div>
                                    <div class="table-uploader">
                                      <file-pond
                                          label-idle="Drop Side Gallery Image"
                                          :allow-multiple="false"
                                          :ref="'product' + product.id + 'SideGalleryImage'"
                                          allow-drop="true"
                                          style-panel-aspect-ratio="1:1"
                                          style-panel-layout="compact"
                                          imagePreviewTransparencyIndicator="grid"
                                          v-bind:files="imagesPool[ product.id ][ product.source_product_id ].side"
                                          @processfile="(err, file) => processGalleryImage( file, product, 'side' )"
                                          @removefile="(err, file) => removeGalleryImage( product, 'side' )"
                                      />
                                    </div>
                                  </div>
                                </td>
                                <td v-for="model in models" v-bind:key="model.id">

                                    <div class="table-uploaders">

                                        <div class="table-uploader">

                                            <file-pond
                                                label-idle="Drop Front Image"
                                                :allow-multiple="false"
                                                :ref="'product' + product.id + 'Model' + model.id + 'FrontImage'"
                                                allow-drop="true"
                                                style-panel-aspect-ratio="1:1"
                                                style-panel-layout="compact"
                                                imagePreviewTransparencyIndicator="grid"
                                                v-bind:files="imagesPool[ product.id ][ model.id ].front"
                                                @processfile="(err, file) => processImage( file, product, 'front', model.id )"
                                                @removefile="(err, file) => removeImage( product, 'front', model.id )"
                                            />

                                        </div>
                                        <!-- /.table-uploader -->

                                        <div class="table-uploader" v-if="partner.is_rotate_enabled">

                                            <file-pond
                                                label-idle="Drop Back Image"
                                                :allow-multiple="false"
                                                :ref="'product' + product.id + 'Model' + model.id + 'BackImage'"
                                                allow-drop="true"
                                                style-panel-aspect-ratio="1:1"
                                                style-panel-layout="compact"
                                                imagePreviewTransparencyIndicator="grid"
                                                v-bind:files="imagesPool[ product.id ][ model.id ].back"
                                                @processfile="(err, file) => processImage( file, product, 'back', model.id )"
                                                @removefile="(err, file) => removeImage( product, 'back', model.id )"
                                            />

                                        </div>
                                        <!-- /.table-uploader -->

                                    </div>
                                    <!-- /.table-uploaders -->

                                </td>
                            </tr>

                            </tbody>

                        </table>
                        <!-- /.table -->

                    </div>
                    <!-- /.table-scroll -->

                    <div class="align--center margin--t-30">

                        <pagination :data="paginationData" :align="'center'" :limit="5" @pagination-change-page="retrieveProducts"></pagination>

                    </div>
                    <!-- /.align--center margin--t-30 -->

                </div>

                <div v-else>

                    <div class="empty-text">

                        No product has been imported yet.

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

import Pagination from 'laravel-vue-pagination';

export default {

    name: 'products-list',

    props: [

        'partner'
    ],

    inject: [
        'getUrl'
    ],

    components: {

        Loader,
        Pagination
    },

    data() {

        return {

            isLoading: false,
            products: [],
            models: this.partner.models,
            imagesPool: {},
            paginationData: {}
        };
    },

    methods: {

        retrieveProducts( page = 1 ) {

            if (this.isLoading) {

                return;
            }

            this.isLoading = true;

            axios.get(
                options.urls['api.partners.products.list']
                    .replace( '%PARTNER%', this.partner.id ),
                {

                    params: {
                        page: page
                    }
                }
            )
                .then((response) => {

                    if (response) {

                        if (response.data) {

                            response = response.data;
                        }

                        if( response.data ) {

                            for( let product of response.data ) {

                                this.initProductImagesPool( product );
/*
                                for ( let variant of product.variants ) {

                                    this.initProductImagesPool( variant );
                                }*/
                            }

                            this.paginationData = {

                                links: response.links,
                                meta: response.meta
                            };

                            this.products = response.data;
                        }
                    }
                })
                .finally( () => {

                    this.isLoading = false;

                } );
        },

        initProductImagesPool( product ) {

            this.imagesPool[ product.id ] = {};

            // Gallery
            this.imagesPool[ product.id ][ product.source_product_id ] = {
                front: [],
                back: [],
                side: []
            };
            for ( let gallery_image of product.gallery ) {
              if( ! gallery_image.source_product_id ) {
                continue;
              }
              if ( typeof this.imagesPool[ product.id ][ gallery_image.source_product_id ] != 'undefined' ) {
                this.imagesPool[ product.id ][ gallery_image.source_product_id ][ gallery_image.side ].push( {
                  source: gallery_image.id,
                  options: {
                    type: 'local',
                  },
                  file: {
                    name: gallery_image.file_name
                  }
                } )
              }
            }


            // Models
            for( let model of this.models ) {

                this.imagesPool[ product.id ][ model.id ] = {

                    front: [],
                    back: []
                };
            }

            for ( let image of product.models ) {
                if( ! image.model_id ) {
                    continue;
                }
                this.imagesPool[ product.id ][ image.model_id ][ image.side ].push( {
                    source: image.id,
                    options: {
                        type: 'local',
                    },
                    file: {
                        name: image.file_name
                    }
                } )
            }

        },

        initPond() {

            const FilePondNative = require( 'filepond' );

            FilePondNative.setOptions( {

                name: 'file',
                allowReorder: true,
                server: {
                    url: options.urls[ 'filepond' ],
                    process: '/process',
                    revert: '/process',
                    load: '/load/',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }
            } );

            const ponds = $( '.filepond--root' );

            ponds.on( 'FilePond:processfilestart', () => {
                this.isProcessingFiles = true;
            } );

            ponds.on( 'FilePond:processfile', () => {
                this.isProcessingFiles = false;
            } );

        },

        processGalleryImage( file, product, side ) {
          axios.put(
            options.urls[ 'api.products.galleryimage' ].replace( '%PRODUCT%', product.id ),
            {
              image: {
                id: file.serverId,
                name: file.filename,
              },
              side: side,
            }
          ).then( ( response ) => {

            if( response && response.data ) {
              response = response.data;
            }

            if( response.success ) {
              this.$notify( {
                group: 'global',
                title: 'Image uploaded!',
                type: 'success'
              } );
            }
          } );
        },

        removeGalleryImage( product, side ) {
          axios.delete(
              options.urls[ 'api.products.galleryimage' ]
                  .replace( '%PRODUCT%', product.id ),
              {
                  data: {
                      side: side
                  }
              }
          )
              .then( ( response ) => {

                  if( response && response.data ) {

                      response = response.data;
                  }

                  if( response.success ) {

                      this.$notify( {

                          group: 'global',
                          title: 'Image deleted!',
                          type: 'error'
                      } );
                  }
              } );
        },

        processImage( file, product, side, modelId ) {

            axios.put(
                options.urls[ 'api.products.image' ]
                    .replace( '%PRODUCT%', product.id ),
                {
                    image: {
                        id: file.serverId,
                        name: file.filename,
                    },
                    side: side,
                    model: modelId
                }
            )
                .then( ( response ) => {

                    if( response && response.data ) {

                        response = response.data;
                    }

                    if( response.success ) {

                        this.$notify( {

                            group: 'global',
                            title: 'Image uploaded!',
                            type: 'success'
                        } );
                    }
                } );

        },

        removeImage( product, side, modelId ) {

            axios.delete(
                options.urls[ 'api.products.image' ]
                    .replace( '%PRODUCT%', product.id ),
                {
                    data: {
                        side: side,
                        model: modelId
                    }
                }
            )
                .then( ( response ) => {

                    if( response && response.data ) {

                        response = response.data;
                    }

                    if( response.success ) {

                        this.$notify( {

                            group: 'global',
                            title: 'Image deleted!',
                            type: 'error'
                        } );
                    }
                } );

        },
    },

    mounted() {

        this.initPond();

        this.retrieveProducts();

    }

}

</script>
