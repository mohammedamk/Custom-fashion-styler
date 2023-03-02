<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use App\Models\PartnerIntegration;
use App\Models\ShopifyIntegration;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPShopify\Shop;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ShopifyWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $signature = $request->header( 'x-shopify-hmac-sha256' );

        if( ! $this->verify_signature( $request->getContent(), $signature ) )
        {
            throw new UnauthorizedHttpException( 'Invalid Shopify Webhook.' );
        }

        if( $shop_domain = $request->header( 'x-shopify-shop-domain' ) )
        {

            $shopify_integrations = ShopifyIntegration::query()
                ->where( 'shopify_url', '=', $shop_domain )
                ->get();

            if( count( $shopify_integrations ) < 1 )
            {
                throw new UnauthorizedHttpException( 'Invalid Shopify Webhook' );
            }

            $matched_partner = false;

            $route_partner = $request->route( 'partner' );

            foreach ( $shopify_integrations as $shopify_integration )
            {

                /**
                 * @var ShopifyIntegration $shopify_integration
                 */

                if( ! $integration = $shopify_integration->integration()->first() )
                {
                    throw new UnauthorizedHttpException( 'Invalid Shopify Webhook' );
                }

                /**
                 * @var PartnerIntegration $integration
                 */

                if( $partner = $integration->partner()->first() )
                {

                    /**
                     * @var Partner $partner
                     */

                    if( $partner->id === ( $route_partner instanceof Partner ? $route_partner->id : $route_partner ) )
                    {

                        $matched_partner = true;
                        break;
                    }
                }
            }

            if( ! $matched_partner )
            {
                throw new UnauthorizedHttpException( 'Invalid Shopify Webhook' );
            }
        }

        return $next($request);
    }

    private function verify_signature( $data, $hmac_header )
    {

        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, config( 'services.shopify.api_secret' ), true));

        return hash_equals($hmac_header, $calculated_hmac);
    }
}
