<?php

namespace App\Http\Controllers\API\PublicControllers\Integrations;

use App\Http\Controllers\API\APIController;
use App\Http\Controllers\Controller;
use App\Models\PartnerInvite;
use App\Models\ShopifyIntegration;
use Illuminate\Http\Request;
use PHPShopify\ShopifySDK;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ShopifyController extends APIController
{

    public function connect( PartnerInvite $invite, Request $request )
    {

        $integration_model = $request->attributes->get( 'integration_model' );

        /**
         * @var ShopifyIntegration $integration_model
         */

        if( ! $url = $integration_model->getActivationUrl() )
        {
            throw new UnprocessableEntityHttpException( "Could not connect to Shopify." );
        }

        return [
            'success'       => true,
            'redirect'      => $url
        ];
    }

}
