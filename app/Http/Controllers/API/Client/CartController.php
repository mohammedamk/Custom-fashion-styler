<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Cart\CheckoutRequest;
use App\Interfaces\Integration;
use App\Models\Partner;
use App\Models\PartnerIntegration;
use App\Models\Product;
use App\Traits\Integrated;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use Illuminate\Support\Facades\Log;
use App\Models\ShopifyIntegration;
use PHPShopify\ShopifySDK;

class CartController extends ClientController
{

  public function orderCreation( Request $request ) {

    if ( !$request->hasHeader( 'x-shopify-shop-domain' ) ) {
      return;
    }

    $shop = $request->header( 'x-shopify-shop-domain' );

    $request_all = $request->all();

    // Log::info( '$product_data: ' . gettype($request_all) . ' ' . json_encode( $request_all ) );

    $orderID = $request_all['id'];

    $is_referred_by_modamatch = false;

    $line_items = $request_all['line_items'];


    foreach ( $line_items as $line_item ) {
      $line_item_properties = $line_item['properties'];
      foreach ( $line_item_properties as $line_item_property ) {
        if ( $line_item_property['name'] == '_referred_by' && $line_item_property['value'] == 'Modamatch' ) {
          $is_referred_by_modamatch = true;
          break 2;
        }
      }
    }

    if ( !$is_referred_by_modamatch ) {
      return;
    }

    $ShopifyIntegration = ShopifyIntegration::firstWhere( 'shopify_url', $shop );


    if ( !$ShopifyIntegration ) {
      return;
    }

    // Log::info( '$ShopifyIntegration: ' . json_encode( $ShopifyIntegration ) );


    if ( !$ShopifyIntegration->access_token ) {
      return;
    }

    $_wrapper = new \PHPShopify\ShopifySDK([
      'ShopUrl' => $ShopifyIntegration->shopify_url,
      'AccessToken' => $ShopifyIntegration->access_token,
    ]);

    try {
      // Get the order by id
      $resource = $_wrapper->Order($orderID)->get();

      $resource_tags = $resource['tags'];

      $new_resource_tags = '';

      if ( $resource_tags ) {
        $resource_tags_arr = explode( ', ', $resource_tags );
        if ( in_array( 'modamatch', $resource_tags_arr ) ) {
          return;
        }
        array_push( $resource_tags_arr, 'modamatch' );
        $new_resource_tags = implode( ', ', $resource_tags_arr );
      } else {
        $new_resource_tags = 'modamatch';
      }

      $updateInfo = array (
        "tags" => $new_resource_tags
      );
      $resource2 = $_wrapper->Order($orderID)->put($updateInfo);

    } catch (\Exception $e) {

    }

  }

    public function checkout( Partner $partner, CheckoutRequest $request )
    {

        $products = $partner->products()->whereIn( 'id', $request->get( 'products' ) )
            ->get();

        if( ! $integration = $partner->integration()->first() )
        {
            throw new UnprocessableEntityHttpException( "Unable to checkout from this partner." );
        }

        /**
         * @var PartnerIntegration $integration
         */

        if( ! $wrapper = $integration->integration()->first() )
        {
            throw new UnprocessableEntityHttpException( "Unable to checkout from this partner." );
        }

        /**
         * @var Integration $wrapper
         */

        /**
         * @var Product[] $products
         */

        if( ! $checkout_url = $wrapper->checkout( $products ) )
        {
            throw new UnprocessableEntityHttpException( "Unable to checkout from this partner." );
        }

        return [

            'success'       => true,
            'checkout_url'  => $checkout_url
        ];
    }

}
