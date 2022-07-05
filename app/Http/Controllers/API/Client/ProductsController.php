<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductResource;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductsController extends ClientController
{

    public function retrieveProducts( Request $request, ProductCategory $category = null, Partner $partner = null ) {

        if ( !isset( $category ) )
        {
            $query = $partner->products();
        }
        else
        {
            $query = $category->products();
        }

        $query->whereNull( 'variant_for' )
            ->whereHas( 'category' )
            ->scopes( 'inStock' );

        $query->with( 'variants', function( $query ) {

            /**
             * @var Builder $query
             */

            $query->scopes( 'inStock' );

        } );


        $products = $query->get();


        return ProductResource::collection( $products );
    }

    public function retrieveCategories( Partner $partner )
    {

        $categories = $partner->product_categories()
            ->orderBy( 'order', 'asc' )
            ->orderBy( 'name', 'asc' )
            ->get();

        return JsonResource::collection( $categories );
    }

    public function retrieveProduct( $product_id, Partner $partner )
    {

        $query = $partner->products();

        $query
            ->where( 'id', '=', $product_id )
            ->scopes( 'inStock' );

        $query->with( 'variants', function( $query ) {

            $query->scopes( 'inStock' );

        } );

        $product = $query->first();

        return [

            'success' => true,
            'product' => new ProductResource( $product )
        ];
    }

    public function retrieveProductFromSource( $source_id, Partner $partner )
    {

        $query = $partner->products();

        $query
            ->where( 'source_product_id', '=', $source_id )
            ->scopes( 'inStock' );

        $query->with( 'variants', function( $query ) {

            $query->scopes( 'inStock' );

        } );

        if ( !$product = $query->first() )
        {
            throw new NotFoundHttpException( 'No such product.' );
        }

        /**
         * @var Product $product
         */

        $variant = null;

        if ( $parent_product = $product->parent_product()->first() )
        {
            $variant = $product;
            $product = $parent_product->with( 'variants', function( $query ) {

                $query->scopes( 'inStock' );

            } );
        }

        return [

            'success' => true,
            'product' => new ProductResource( $product ),
            'variant' => $variant ? new ProductResource( $variant ) : null,
        ];
    }

}
