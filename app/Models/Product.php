<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [

        'title',
        'description',
        'price',
        'partner_id',
        'source_product_id',
        'source_product_type',
        'product_category_id',
        'option1_choices',
        'option2_choices',
        'option3_choices',
        'option4_choices',
        'option1',
        'option2',
        'option3',
        'option4',
        'variant_for',
        'is_default_variant',
        'stock',
        'group_id'
    ];

    public $casts = [

        'option1_choices'   => 'json',
        'option2_choices'   => 'json',
        'option3_choices'   => 'json',
        'option4_choices'   => 'json',
    ];

    public function scopeInStock( $query )
    {

        return $query->where( function ( $query ) {

            $query->where( 'stock', '>', 0 )
                ->orWhereHas( 'variants', function ( $variant_query ) {

                    $variant_query->where( 'stock', '>', 0 );

                } );
        } );
    }

    public function variants()
    {
        return $this->hasMany( Product::class, 'variant_for', 'id' );
    }

    public function parent_product()
    {
        return $this->belongsTo( Product::class, 'variant_for', 'id' );
    }

    public function category()
    {
        return $this->belongsTo( ProductCategory::class, 'product_category_id', 'id' );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection( 'image' )
            ->singleFile();

        $this->addMediaCollection( 'models' );

        $this->addMediaCollection( 'gallery' );
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(386)
            ->height(386)
            ->keepOriginalImageFormat()
            ->performOnCollections( 'image' );

        $this->addMediaConversion( 'large' )
            ->height( 1080 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'models' );

        $this->addMediaConversion( 'medium' )
            ->height( 720 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'models' );

        $this->addMediaConversion( 'small' )
            ->height( 480 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'models' );

        // Gallery
        $this->addMediaConversion('thumb')
            ->width(386)
            ->height(386)
            ->keepOriginalImageFormat()
            ->performOnCollections( 'gallery' );

        $this->addMediaConversion( 'large' )
            ->height( 1080 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'gallery' );

        $this->addMediaConversion( 'medium' )
            ->height( 720 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'gallery' );

        $this->addMediaConversion( 'small' )
            ->height( 480 )
            ->keepOriginalImageFormat()
            ->performOnCollections( 'gallery' );

    }

    public function getImageAttribute()
    {

        $image = $this->getMedia( 'image' )->first();

        if( $this->is_variant && ! $image )
        {

            if( $parent_product = $this->parent_product()->first() )
            {
                /**
                 * @var Product $parent_product
                 */

                return $parent_product->image;
            }
        }

        if( $image )
        {
            return [

                'id'        => $image->id,
                'full'      => (string)( ( $image )() ),
                'thumb'     => (string)( ( $image )( 'thumb' ) ),
            ];
        }

        return null;
    }


    public function getGalleryAttribute() {

      if( $this->is_variant && $this->is_default_variant ) {

          if( $parent_product = $this->parent_product()->first() )
          {
              /**
               * @var Product $parent_product
               */

              return $parent_product->gallery;
          }
      }

      $gallery = $this->getMedia( 'gallery' )->all();

      return array_map( function ( $i ) {

          /**
           * @var Media $i
           */

          return [
            'id'                => $i->id,
            'full_url'          => $i->getFullUrl(),
            'full'              => (string)( ( $i )() ),
            'large'             => (string)( ( $i )( 'large' ) ),
            'medium'            => (string)( ( $i )( 'medium' ) ),
            'small'             => (string)( ( $i )( 'small' ) ),
            'thumb'             => (string)( ( $i )( 'thumb' ) ),
            'source_product_id' =>  $i->getCustomProperty( 'source_product_id' ),
            'side'              => $i->getCustomProperty( 'side' ),
            'file_name'         => $i->file_name,
          ];

      }, $gallery );
    }


    public function getModelsAttribute()
    {

        if( $this->is_variant && $this->is_default_variant )
        {

            if( $parent_product = $this->parent_product()->first() )
            {
                /**
                 * @var Product $parent_product
                 */

                return $parent_product->models;
            }
        }

        $models = $this->getMedia( 'models' )->all();

        return array_map( function ( $i ) {

            /**
             * @var Media $i
             */

            return [

                'id'        => $i->id,
                'full_url'  => $i->getFullUrl(),
                'full'      => (string)( ( $i )() ),
                'large'     => (string)( ( $i )( 'large' ) ),
                'medium'    => (string)( ( $i )( 'medium' ) ),
                'small'     => (string)( ( $i )( 'small' ) ),
                'model_id'  => $i->getCustomProperty( 'model' ),
                'side'      => $i->getCustomProperty( 'side' ),
                'file_name' => $i->file_name,
            ];

        }, $models );
    }

    public function getLayerAttribute()
    {

        if( $category = $this->category()->first() )
        {
            return $category->layer;
        }

        return null;
    }

    public function getFormattedTitleAttribute()
    {

        $title = $this->title;

        if( $parent_product = $this->parent_product()->first() )
        {
            if( $this->is_default_variant )
            {
                $title = $parent_product->title;
            }
            else
            {
                $title = $parent_product->title;//$title = $parent_product->title . ' - ' . $title;
            }
        }

        return $title;
    }

    public static function fromObject( $partner, $product_data )
    {

        if( ! $product = $partner->products()->where( 'source_product_id', '=', $product_data[ 'source_product_id' ] )->first() )
        {

            $product = new Product( $product_data );

            if( ! $product->save() )
            {
                return false;
            }
        }

        $product_category = null;

        if( ! empty( $product_data[ 'source_product_type' ] ) )
        {

            if( ! $product_category = $partner->product_categories()->where( 'name', '=', $product_data[ 'source_product_type' ] )->first() )
            {

                $product_category = new ProductCategory( [

                    'name'          => $product_data[ 'source_product_type' ],
                    'partner_id'    => $partner->id
                ] );

                if( ! $product_category->save() )
                {
                    $product_category = null;
                }
            }
        }

        $product_data[ 'group_id' ] = $product->source_product_id;

        $product->fill( $product_data );

        if( $product_category )
        {
            $product->setAttribute( 'product_category_id', $product_category->id );
        }
        else
        {
            $product->setAttribute( 'product_category_id', null );
        }

        $product->save();

        if( ! empty( $product_data[ 'image' ] ) )
        {

            if( $current_image = $product->getMedia( 'image' )->first() )
            {

                /**
                 * @var Media $current_image
                 */

                if( $current_image->getCustomProperty( 'source_path' ) != $product_data[ 'image' ] )
                {

                    $current_image->delete();
                    $current_image = null;
                }
            }

            if( ! $current_image )
            {

                $product->addMediaFromUrl( $product_data[ 'image' ] )
                    ->withCustomProperties( [

                        'source_path'       => $product_data[ 'image' ]

                    ] )
                    ->toMediaCollection( 'image' );
                    // ->toMediaCollection( 'image', 's3' );
            }
        }

        foreach( $product_data[ 'variants' ] as $variant_data )
        {

            if( ! $variant = $product->variants()->where( 'source_product_id', '=', $variant_data[ 'source_product_id' ] )->first() )
            {

                $variant_data[ 'variant_for' ] = $product->id;
                $variant_data[ 'partner_id' ] = $partner->id;

                $variant = $product->variants()->newModelInstance( $variant_data );

                if( ! $variant->save() )
                {
                    continue;
                }
            }

            $variant_data[ 'group_id' ] = $product->source_product_id;

            $variant->fill( $variant_data );

            $variant->save();

            if( ! empty( $variant_data[ 'image' ] ) )
            {

                if( $current_image = $variant->getMedia( 'image' )->first() )
                {

                    /**
                     * @var Media $current_image
                     */

                    if( $current_image->getCustomProperty( 'source_path' ) != $variant_data[ 'image' ] )
                    {

                        $current_image->delete();
                        $current_image = null;
                    }
                }

                if( ! $current_image )
                {

                    $variant->addMediaFromUrl( $variant_data[ 'image' ] )
                        ->withCustomProperties( [

                            'source_path'       => $variant_data[ 'image' ]

                        ] )
                        ->toMediaCollection( 'image' );
                        // ->toMediaCollection( 'image', 's3' );
                }
            }
        }

        return $product;
    }

    public function getIsVariantAttribute()
    {
        return ! empty( $this->variant_for );
    }
}
