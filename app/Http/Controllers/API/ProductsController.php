<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Products\AttachImageRequest;
use App\Http\Requests\API\Products\DettachImageRequest;
use App\Http\Requests\API\Products\AttachGalleryImageRequest;
use App\Http\Requests\API\Products\DettachGalleryImageRequest;
use App\Http\Requests\API\Products\MapCategoriesRequest;
use App\Http\Resources\Products\ProductResource;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Sopamo\LaravelFilepond\Filepond;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProductsController extends APIController
{

    public function list_products( Partner $partner, Request $request )
    {

        $query = $partner->products();

        $query->orderBy( 'group_id', 'asc' )
            ->orderBy( 'variant_for', 'asc' );

        $per_page = $request->get( 'per_page', 21 );

        $current_page = $request->get( 'page', 1 );

        $products = $query->paginate( $per_page, ['*'], 'page', $current_page );

        return ProductResource::collection( $products );
    }

    public function list_categories( Partner $partner )
    {

        $query = $partner->product_categories();

        $query->orderBy( 'name', 'asc' );

        $query->whereHas( 'products' );

        $categories = $query->get();

        return JsonResource::collection( $categories );
    }

    public function attach_galleryimage_to_product( Product $product, AttachGalleryImageRequest $request ) {
      $filepond = app( Filepond::class );
      $current_images = $product->getMedia( 'gallery' )->all();

      $image_side         = $request->get( 'side', 'front' );
      $source_product_id  = $product->source_product_id;

      $current_image = null;

      foreach ( $current_images as $image ) {

          /**
           * @var Media $current_image
           */

          if( $image->getCustomProperty( 'side' ) == $image_side ) {
              if( $image->getCustomProperty( 'source_product_id' ) == $source_product_id ) {
                  $current_image = $image;
                  break;
              }
          }
      }

      if(( $image = $request->get( 'image' ) ) && is_array( $image ) && ! empty( $image[ 'id' ] ) && ! empty( $image[ 'name' ] ) )
      {

          if( $image_path = $filepond->getPathFromServerId( $image[ 'id' ] ) )
          {

              try {

                  if( $current_image )
                  {
                      $current_image->delete();
                  }

                  $temp_disk = config( 'filepond.temporary_files_disk', 'local' );

                  $temp_path = \Storage::disk( $temp_disk )->path( '' );

                  $file_path = str_replace( $temp_path, '', $image_path );

                  $product->addMediaFromDisk( $file_path, $temp_disk )
                      ->setFileName( $image[ 'name' ] )
                      ->withCustomProperties( [

                          'side'              =>  $image_side,
                          'source_product_id' =>  $source_product_id

                      ] )
                      ->toMediaCollection( 'gallery' );
                      // ->toMediaCollection( 'gallery', 's3' );
              }
              catch( FileUnacceptableForCollection $wrongMime )
              {
                  throw ValidationException::withMessages( [

                      'image'            => 'Only image files are accepted.'

                  ] );
              }
              catch( \Exception $e )
              {

                  Log::error( $e->getMessage(), $e->getTrace() );

                  throw new UnprocessableEntityHttpException( "An error occurred while uploading image to product." );
              }
          }
      }
      else
      {

          if( $current_image )
          {
              $current_image->delete();
          }
      }

      return [

          'success'       => true
      ];
    }

    public function detach_galleryimage_from_product( Product $product, DettachGalleryImageRequest $request ) {
      $current_images = $product->getMedia( 'gallery' )->all();

      $image_side = $request->get( 'side', 'front' );
      $source_product_id  = $product->source_product_id;

      $current_image = null;

      foreach ( $current_images as $image )
      {

          /**
           * @var Media $current_image
           */

          if( $image->getCustomProperty( 'side' ) == $image_side )
          {
              if( $image->getCustomProperty( 'source_product_id' ) == $source_product_id )
              {
                  $current_image = $image;
                  break;
              }
          }
      }

      if( $current_image )
      {
          $current_image->delete();
      }

      return [

          'success'       => true
      ];
    }

    public function attach_image_to_product( Product $product, AttachImageRequest $request ) {

        $filepond = app( Filepond::class );

        $current_images = $product->getMedia( 'models' )->all();

        $image_side = $request->get( 'side', 'front' );
        $model_id = $request->get( 'model' );

        $current_image = null;

        foreach ( $current_images as $image )
        {

            /**
             * @var Media $current_image
             */

            if( $image->getCustomProperty( 'side' ) == $image_side )
            {
                if( $image->getCustomProperty( 'model' ) == $model_id )
                {
                    $current_image = $image;
                    break;
                }
            }
        }

        if(( $image = $request->get( 'image' ) ) && is_array( $image ) && ! empty( $image[ 'id' ] ) && ! empty( $image[ 'name' ] ) )
        {

            if( $image_path = $filepond->getPathFromServerId( $image[ 'id' ] ) )
            {

                try {

                    if( $current_image )
                    {
                        $current_image->delete();
                    }

                    $temp_disk = config( 'filepond.temporary_files_disk', 'local' );

                    $temp_path = \Storage::disk( $temp_disk )->path( '' );

                    $file_path = str_replace( $temp_path, '', $image_path );

                    $product->addMediaFromDisk( $file_path, $temp_disk )
                        ->setFileName( $image[ 'name' ] )
                        ->withCustomProperties( [

                            'side'          => $image_side,
                            'model'         => $model_id

                        ] )
                        ->toMediaCollection( 'models' );
                        // ->toMediaCollection( 'models', 's3' );
                }
                catch( FileUnacceptableForCollection $wrongMime )
                {
                    throw ValidationException::withMessages( [

                        'image'            => 'Only image files are accepted.'

                    ] );
                }
                catch( \Exception $e )
                {

                    Log::error( $e->getMessage(), $e->getTrace() );

                    throw new UnprocessableEntityHttpException( "An error occurred while uploading image to product." );
                }
            }
        }
        else
        {

            if( $current_image )
            {
                $current_image->delete();
            }
        }

        return [

            'success'       => true
        ];
    }

    public function detach_image_from_product( Product $product, DettachImageRequest $request )
    {

        $current_images = $product->getMedia( 'models' )->all();

        $image_side = $request->get( 'side', 'front' );
        $model_id = $request->get( 'model' );

        $current_image = null;

        foreach ( $current_images as $image )
        {

            /**
             * @var Media $current_image
             */

            if( $image->getCustomProperty( 'side' ) == $image_side )
            {
                if( $image->getCustomProperty( 'model' ) == $model_id )
                {
                    $current_image = $image;
                    break;
                }
            }
        }

        if( $current_image )
        {
            $current_image->delete();
        }

        return [

            'success'       => true
        ];
    }

    public function map_categories( Partner $partner, MapCategoriesRequest $request )
    {

        $query = $partner->product_categories();

        $categories = $query->get();

        foreach ( $request->get( 'categories' ) as $category_id => $category_map )
        {

            if( ! $category = $categories->where( 'id', '=', $category_id )->first )
            {
                continue;
            }

            /**
             * @var ProductCategory $category
             */

            if( ! isset( $category_map[ 'layer' ] ) )
            {
                $category_map[ 'layer' ] = null;
            }

            $category->setAttribute( 'layer', $category_map[ 'layer' ] );

            $category->save();
        }

        return [

            'success'           => true
        ];
    }

}
