<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerIntegration;
use App\Models\Product;
use App\Models\ShopifyIntegration;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ShopifyController extends WebhooksController
{

    public function products_create( Partner $partner, Request $request )
    {

        $payload = $request->getContent();

        try {

            $payload = json_decode( $payload, true );
        }
        catch ( \Exception $e ) {

            throw new UnprocessableEntityHttpException( 'Invalid product payload.' );
        }

        if( empty( $payload[ 'id' ] ) )
        {
            throw new UnprocessableEntityHttpException( 'Invalid product payload.' );
        }

        $product_data = ShopifyIntegration::createProductObject( $partner, $payload );

        Product::fromObject( $partner, $product_data );

    }

    public function products_update( Partner $partner, Request $request )
    {

        $payload = $request->getContent();

        try {

            $payload = json_decode( $payload, true );
        }
        catch ( \Exception $e ) {

            throw new UnprocessableEntityHttpException( 'Invalid product payload.' );
        }

        if( empty( $payload[ 'id' ] ) )
        {
            throw new UnprocessableEntityHttpException( 'Invalid product payload.' );
        }

        $product_data = ShopifyIntegration::createProductObject( $partner, $payload );

        Product::fromObject( $partner, $product_data );

    }

    public function products_delete( Partner $partner, Request $request )
    {

        $payload = $request->getContent();

        try {

            $payload = json_decode( $payload, true );
        }
        catch ( \Exception $e ) {

            throw new UnprocessableEntityHttpException( 'Invalid product payload.' );
        }

        if( empty( $payload[ 'id' ] ) )
        {
            throw new UnprocessableEntityHttpException( 'Invalid product payload.' );
        }

        $found_products = $partner->products()->where( 'source_product_id', '=', $payload[ 'id' ] )->get();

        foreach ( $found_products as $found_product )
        {

            /**
             * @var Product $found_product
             */

            $found_product->variants()->delete();

            $found_product->delete();
        }

    }

    public function gdpr_request_data( Request $request )
    {

        $payload = $request->getContent();

        try {

            $payload = json_decode( $payload );
        }
        catch( \Exception $e ) {

            $payload = (object) [];
        }

        $returned_data = [];

        if( ! empty( $payload->shop_domain ) )
        {

            if( ShopifyIntegration::query()->where( 'shopify_url', '=', $payload->shop_domain )->exists() )
            {
                $returned_data[ 'shop_domain' ] = $payload->shop_domain;
            }
        }

        return response()->json( $returned_data );
    }

    public function gdpr_customers_redact( Request $request )
    {
        return response()->json( [

            'success'       => true

        ] );
    }

    public function gdpr_shop_redact( Request $request )
    {

        $payload = $request->getContent();

        try {

            $payload = json_decode( $payload );
        }
        catch( \Exception $e ) {

            $payload = (object) [];
        }

        $returned_data = [];

        if( ! empty( $payload->shop_domain ) )
        {

            $integrations = ShopifyIntegration::query()->where( 'shopify_url', '=', $payload->shop_domain )->get();

            foreach ( $integrations as $integration )
            {

                /**
                 * @var ShopifyIntegration $integration
                 */

                if( $partner_integration = $integration->integration()->first() )
                {

                    /**
                     * @var PartnerIntegration $partner_integration
                     */

                    if( $partner = $partner_integration->partner()->first() )
                    {

                        /**
                         * @var Partner $partner
                         */

                        $partner->products()->whereNotNull( 'variant_for' )->delete();

                        $partner->products()->delete();

                        $partner->product_categories()->delete();

                        $integration->delete();

                        $partner_integration->delete();
                    }
                }
            }
        }

        return response()->json( [

            'success'           => true

        ] );
    }

}
