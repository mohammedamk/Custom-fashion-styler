<?php

namespace App\Http\Controllers\PublicControllers;


use App\Models\Partner;
use App\Models\PartnerIntegration;
use App\Models\PartnerInvite;
use App\Models\ShopifyIntegration;
use App\Notifications\Partners\ImportReadyNotification;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use PHPShopify\ShopifySDK;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class PartnersController extends PublicController
{

    public function invite( PartnerInvite $invite, Request $request )
    {

        $partner = $request->attributes->get( 'partner' );
        $integration_model = $request->attributes->get( 'integration_model' );

        $this->setTitle( [ $integration_model->integration_name, 'Integrations' ] );

        session()->put( 'invite', $invite->id );
        session()->put( 'secret', $invite->secret_key );

        return view( 'public/partners/invite', [

            'partner'           => $partner,
            'integration'       => $integration_model,
            'invite'            => $invite->id,
            'secret'            => $invite->secret_key

        ] );
    }

    public function complete( Request $request )
    {

        $invite_id = session()->get( 'invite' );
        $invite_secret = session()->get( 'secret' );

        $invite = PartnerInvite::query()
            ->where( 'id', '=', $invite_id )
            ->where( 'secret_key', '=', $invite_secret )
            ->first();

        if( ! $invite )
        {
            throw new UnauthorizedHttpException( "Invalid invite link." );
        }

        /**
         * @var PartnerInvite $invite
         */

        if( ! $partner = $invite->partner()->first() )
        {
            throw new UnauthorizedHttpException( "Invalid invite link." );
        }

        /**
         * @var Partner $partner
         */

        if( ! $integration = $partner->integration()->first() )
        {
            throw new UnauthorizedHttpException( "Invalid invite link." );
        }

        /**
         * @var PartnerIntegration $integration
         */

        if( ! $integration_model = $integration->integration()->first() )
        {
            throw new UnauthorizedHttpException( "Invalid invite link." );
        }

        /**
         * @var ShopifyIntegration $integration_model
         */

        $config = [

            'ShopUrl' => $integration_model->shopify_url,
            'ApiKey' => config( 'services.shopify.api_key' ),
            'SharedSecret' => config( 'services.shopify.api_secret' ),
        ];

        ShopifySDK::config($config);

        $access_token = \PHPShopify\AuthHelper::getAccessToken();

        $integration_model->setAttribute( 'access_token', $access_token );

        $integration_model->save();

        $integration_model->createWebhooks();

        $invite->delete();

        Notification::route( 'mail', 'james@moda-match.com' )
            ->notify( new ImportReadyNotification( $partner ) );

        return redirect( route( 'public.partners.invite.thank-you' ) );
    }

    public function thank_you()
    {

        $this->setTitle( [ 'Thank you' ] );

        return view( 'public.partners.thank-you' );
    }

}
