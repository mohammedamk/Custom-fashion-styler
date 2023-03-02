<?php

namespace App\Models;

use App\Interfaces\Integration;
use App\Jobs\Partners\ImportJob;
use App\Traits\Integrated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use PHPShopify\ShopifySDK;
use Shopify\Auth\FileSessionStorage;
use Shopify\Clients\Rest;
use Shopify\Context;

use Illuminate\Support\Facades\Log;

class ShopifyIntegration extends Model implements Integration
{
    use HasFactory, Integrated, DispatchesJobs;

    public $integration_name = 'Shopify';

    protected $fillable = [

        'access_token',
        'refresh_token',
        'expire_at',
        'shopify_url',
        'shopify_host',
        'private_app_key',
        'private_app_secret'
    ];

    protected $hidden = [

        'access_token',
        'refresh_token',
        'expire_at'
    ];

    /**
     * @var ShopifySDK|null
     */
    private $_wrapper = null;

    /**
     * @var Rest|null
     */
    private $_restWrapper = null;

    public static $shopify_scopes = [

        'read_products',
        'write_checkouts',
        'read_checkouts'
    ];

    public function getIsActiveAttribute()
    {

        return ! empty( $this->access_token ) && ! empty( $this->is_sales_channel_installed );
    }

    public function import()
    {

        $job = new ImportJob( $this );

        $this->dispatch( $job );

        return $job->getJobStatusId();
    }

    public function getActivationUrl()
    {

        if( ! $this->shopify_url )
        {
            return false;
        }

        $config = [

            'ShopUrl' => $this->shopify_url,
            'ApiKey' => config( 'services.shopify.api_key' ),
            'SharedSecret' => config( 'services.shopify.api_secret' ),
        ];

        ShopifySDK::config($config);

        return \PHPShopify\AuthHelper::createAuthRequest(
            self::$shopify_scopes,
            route( 'public.partners.invite.complete' ),
            null,
            null,
            true
        );
    }

    public function getShopifyWrapper()
    {

        if( ! isset( $this->_wrapper ) )
        {

            $this->_wrapper = new \PHPShopify\ShopifySDK( [

                'ShopUrl' => $this->shopify_url,
                'AccessToken' => $this->access_token,
            ] );
        }

        return $this->_wrapper;
    }

    public function getShopifyRestWrapper()
    {

        if( ! isset( $this->_restWrapper ) )
        {

            Context::initialize(
                $this->getApiKey(),
                $this->getApiSecret(),
                self::$shopify_scopes,
                parse_url( config( 'app.url' ), PHP_URL_HOST ),
                new FileSessionStorage('/tmp/php_sessions'),
                '2021-10'
            );

            $this->_restWrapper = new Rest( $this->shopify_url, $this->access_token );
        }

        return $this->_restWrapper;
    }

    public function getApiKey()
    {

        $key = config( 'services.shopify.api_key' );

        if( ! $this->is_sales_channel_installed )
        {
            $key = $this->private_app_key;
        }

        return $key;
    }

    public function getApiSecret()
    {

        $secret = config( 'services.shopify.api_secret' );

        if( ! $this->is_sales_channel_installed )
        {
            $secret = $this->private_app_secret;
        }

        return $secret;
    }

    public function retrieveProducts()
    {

        $wrapper = $this->getShopifyWrapper();

        $products = [];

        $next = null;

        do {

            $resource = $wrapper->Product;

            $wrapper_products = $resource->get( [], $next );

            foreach ( $wrapper_products as $wrapper_product )
            {
                try {
                    if ( str_contains( $wrapper_product['tags'], 'modamatch' ) ) {

                      // Log::info( '$wrapper_product: ' . json_encode( $wrapper_product ) );

                      $products[] = ShopifyIntegration::createProductObject( $this->getPartnerAttribute(), $wrapper_product );
                    }
                }
                catch( \Exception $e ) {}
            }

        }
        while( $next = $resource->getNextLink() );

        return $products;
    }

    public static function createProductObject( $partner, $wrapper_product )
    {

        $product_image = null;

        if( isset( $wrapper_product[ 'image' ] ) && is_array( $wrapper_product[ 'image' ] ) && ! empty( $wrapper_product[ 'image' ][ 'src' ] ) )
        {
            $product_image = $wrapper_product[ 'image' ][ 'src' ];
        }

        $product_images = [];
        if( is_array( $wrapper_product[ 'images' ] ) && count( $wrapper_product[ 'images' ] ) ) {
          foreach ( $wrapper_product[ 'images' ] as $wrapper_product_images ) {
            if( ! empty( $wrapper_product_images[ 'src' ] ) ) {
              $product_images[] = $wrapper_product_images[ 'src' ];
            }
          }
        }

        $product_data = [
            'title'               =>  $wrapper_product[ 'title' ],
            'description'         =>  $wrapper_product[ 'body_html' ],
            'partner_id'          =>  $partner->id,
            'price'               =>  null,
            'source_product_type' =>  $wrapper_product[ 'product_type' ],
            'source_product_id'   =>  $wrapper_product[ 'id' ],
            'image'               =>  $product_image,
            'images'              =>  $product_images,
            'variants'            =>  []
        ];

        if( isset( $wrapper_product[ 'options' ] ) && is_array( $wrapper_product[ 'options' ] ) )
        {

            foreach ( $wrapper_product[ 'options' ] as $variant_options )
            {

                $product_data[ 'option' . $variant_options[ 'position' ] . '_choices' ] = $variant_options[ 'values' ];

            }
        }

        foreach ( $wrapper_product[ 'variants' ] as $wrapper_variant )
        {

            $variant_data = [

                'title'         => $wrapper_variant[ 'title' ],
                'price'         => $wrapper_variant[ 'price' ],
                'source_product_id' => $wrapper_variant[ 'id' ],
                'stock'         => $wrapper_variant[ 'inventory_quantity' ]

            ];

            if( isset( $wrapper_product[ 'images' ] ) && is_array( $wrapper_product[ 'images' ] ) && count( $wrapper_product[ 'images' ] ) > 0 )
            {

                foreach ( $wrapper_product[ 'images' ] as $wrapper_image )
                {

                    if( in_array( $wrapper_variant[ 'id' ], $wrapper_image[ 'variant_ids' ] ) )
                    {

                        $variant_data[ 'image' ] = $wrapper_image[ 'src' ];
                        break;
                    }
                }
            }

            for( $i = 0; $i < 4; $i++ )
            {

                $variant_index = $i + 1;

                if( isset( $wrapper_variant[ 'option' . $variant_index ] ) )
                {
                    $variant_data[ 'option' . $variant_index ] = $wrapper_variant[ 'option' . $variant_index ];
                }
            }

            if( $wrapper_variant[ 'title' ] == 'Default Title' )
            {
                $variant_data[ 'is_default_variant' ] = true;
                $variant_data[ 'option1' ] = null;
                $product_data[ 'option1_choices' ] = null;
            }

            $product_data[ 'variants' ][] = $variant_data;
        }

        if( ! isset( $product_data[ 'price' ] ) )
        {
            foreach ( $product_data[ 'variants' ] as $variant_data )
            {
                $product_data[ 'price' ] = $variant_data[ 'price' ];
                break;
            }
        }

        return $product_data;
    }

    public function checkout( $products )
    {

        $line_items = [];

        foreach ( $products as $product )
        {

            $variant_id = null;
            $product_id = null;

            if( $parent_product = $product->parent_product()->first() )
            {
                $variant_id = $product->source_product_id;
                $product_id = $parent_product->source_product_id;
            }
            else
            {
                $product_id = $product->source_product_id;
            }

            if( isset( $variant_id ) )
            {
                $line_items[] = [
                    'variant_id'        => $variant_id,
                    'quantity'          => 1
                ];
            }
            else
            {
                $line_items[] = [
                    'variant_id'        => $product_id,
                    'quantity'          => 1
                ];
            }
        }

        $wrapper = $this->getShopifyRestWrapper();

        $response = $wrapper->post(
            "checkouts", [
                'checkout'      => [

                    "line_items" => $line_items

                ]
            ]
        );

        if( $response && $response = $response->getDecodedBody() )
        {

            if( isset( $response[ 'checkout' ] ) )
            {
                return $response[ 'checkout' ][ 'web_url' ];
            }
        }

        return false;
    }

    public function createWebhooks()
    {

        $wrapper = $this->getShopifyWrapper();

        $webhooks_to_create = [

            'products/create'       => route( 'webhooks.shopify.products.create', [ 'partner' => $this->partner ] ),
            'products/update'       => route( 'webhooks.shopify.products.update', [ 'partner' => $this->partner ] ),
            'products/delete'       => route( 'webhooks.shopify.products.delete', [ 'partner' => $this->partner ] )

        ];

        foreach ( $webhooks_to_create as $topic => $address )
        {

            try {

                $existing_webhooks = $wrapper->Webhook->get( [

                    'address'      => $address

                ] );

                if( $existing_webhooks && is_array( $existing_webhooks ) && count( $existing_webhooks ) > 0 )
                {

                    foreach ( $existing_webhooks as $existing_webhook )
                    {
                        try {

                            $wrapper->Webhook($existing_webhook[ 'id' ])->delete();
                        }
                        catch( \Exception $e ) {}
                    }

                }

            }
            catch( \Exception $e ) {}

            try {

                $wrapper->Webhook->post( [

                    'topic'     => $topic,
                    'address'   => $address

                ] );
            }
            catch ( \Exception $e ) {}
        }

        $this->save();
    }

    public function getButtonCodeAttribute()
    {
        return '<a href="#" class="moda-match-button" data-moda-match="{%- if product.selected_variant -%}{{product.selected_variant.id}}{%- else -%}{{product.id}}{%- endif -%}">Virtual Fitting Room</a>';
    }
}
