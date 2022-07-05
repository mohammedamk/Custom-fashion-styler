<?php

namespace App\Http\Controllers\PublicControllers;

use App\Interfaces\Integration;
use App\Models\Partner;
use App\Models\PartnerIntegration;
use App\Models\PartnerInvite;
use App\Models\ShopifyIntegration;
use App\Notifications\Partners\ImportReadyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use PHPShopify\ShopifySDK;
use Shopify\Auth\FileSessionStorage;
use Shopify\Clients\Rest;
use Shopify\Context;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class IntegrationsController extends PublicController
{

    public function shopify( Request $request )
    {

        return response()->view( 'public.partners.integrations.shopify.iframe', [

                'api_key'           => config( 'services.shopify.api_key' ),
                'host'              => $request->get( 'host' ),
                'shop'              => $request->get( 'shop' ),
                'scopes'            => join( ',', ShopifyIntegration::$shopify_scopes ),
                'redirect_uri'      => route( 'public.integrations.shopify.activate' )

            ] )
            ->withHeaders( [

                'Content-Security-Policy'       => 'frame-ancestors https://' . $request->get( 'shop' ) . ' https://admin.shopify.com;'

            ] );
    }

    public function activate( Request $request )
    {

        $shop_url = $request->get( 'shop' );

        $config = [

            'ShopUrl' => $shop_url,
            'ApiKey' => config( 'services.shopify.api_key' ),
            'SharedSecret' => config( 'services.shopify.api_secret' ),
        ];

        ShopifySDK::config($config);

        $access_token = \PHPShopify\AuthHelper::getAccessToken();

        if( ! $integration = ShopifyIntegration::query()->where( 'shopify_url', '=', $shop_url )->first() )
        {

            $integration = new ShopifyIntegration( [

                'shopify_url'           => $shop_url,
                'access_token'          => $access_token,
                'shopify_host'          => $request->get( 'host' )

            ] );

            if( ! $integration->save() )
            {
                throw new UnprocessableEntityHttpException( "An error occurred while activating your shop." );
            }
        }

        $integration->fill( [

            'shopify_url'           => $shop_url,
            'access_token'          => $access_token,
            'shopify_host'          => $request->get( 'host' )

        ] );

        $integration->setAttribute( 'is_sales_channel_installed', true );

        $integration->save();

        $wrapper = $integration->getShopifyRestWrapper();

        $shop_details = $wrapper->get( 'shop' )->getDecodedBody();

        if ( ! is_array( $shop_details ) || ! isset( $shop_details[ 'shop' ] ) )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while activating your shop." );
        }

        $shop_details = $shop_details[ 'shop' ];

        if ( ! $partner = Partner::query()->where( 'email', '=', $shop_details[ 'email' ] )->first() )
        {

            $partner = new Partner( [

                'name'       => $shop_details[ 'name' ],
                'email'      => $shop_details[ 'email' ],
                'is_pending' => 1

            ] );

            if ( !$partner->save() )
            {
                throw new UnprocessableEntityHttpException( "An error occurred while activating your shop." );
            }
        }

        $partner_integration = new PartnerIntegration( [

            'integration_type' => ShopifyIntegration::class,
            'integration_id'   => $integration->id,
            'partner_id'       => $partner->id

        ] );

        if( ! $partner_integration->save() )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while activating your shop." );
        }

        $this->setTitle( [ 'Thank you' ] );

        return redirect(
            route( 'public.integrations.shopify.manage' ) . '?host=' . $integration->shopify_host
        );
    }

    public function manage( Request $request )
    {

        if( ! $shopify_integration = ShopifyIntegration::query()->where( 'shopify_host', '=', $request->get( 'host' ) )->first() )
        {
            throw new NotFoundHttpException( 'Partner not found.' );
        }

        return response()
            ->view( 'public.partners.integrations.shopify.manage', [

                'api_key'           => config( 'services.shopify.api_key' ),
                'host'              => $shopify_integration->shopify_host,
                'shop'              => $shopify_integration->shopify_url

            ] )
            ->withHeaders( [

                'Content-Security-Policy'       => 'frame-ancestors https://' . $shopify_integration->shopify_url . ' https://admin.shopify.com;'

            ] );
    }
}