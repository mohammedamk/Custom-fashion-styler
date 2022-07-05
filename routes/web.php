<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get( '/filepond/api', function() {
    return response();
})->name( 'filepond' );

Route::get( '/filepond/api/load/{id}', [ \App\Http\Controllers\FilePondOverrideController::class, 'load' ] )->name( 'filepond.load' );

Route::get( '/login', [ \App\Http\Controllers\AuthController::class, 'login_form' ] )->name( 'login' );
Route::post( '/login', [ \App\Http\Controllers\AuthController::class, 'login' ] )->name( 'login.process' );

Route::prefix( '/' )->middleware( [ 'auth:sanctum', 'can:admin' ] )->group( function() {

    Route::get( '/', [ \App\Http\Controllers\Dashboard\DashboardController::class, 'home' ] )->name( 'dashboard' );

    Route::get( '/partners', [ \App\Http\Controllers\Dashboard\PartnersController::class, 'list_all' ] )->name( 'dashboard.partners' );
    Route::get( '/partners/new', [ \App\Http\Controllers\Dashboard\PartnersController::class, 'create_form' ] )->name( 'dashboard.partners.new' );
    Route::get( '/partners/{partner}/edit', [ \App\Http\Controllers\Dashboard\PartnersController::class, 'edit_form' ] )->name( 'dashboard.partners.edit' );
    Route::get( '/partners/{partner}/products', [ \App\Http\Controllers\Dashboard\ProductsController::class, 'list_products' ] )->name( 'dashboard.partners.products' );

    Route::get( '/models', [ \App\Http\Controllers\Dashboard\ModelsController::class, 'list_all' ] )->name( 'dashboard.models' );
    Route::get( '/models/new', [ \App\Http\Controllers\Dashboard\ModelsController::class, 'create_form' ] )->name( 'dashboard.models.new' );
    Route::get( '/models/{model}/edit', [ \App\Http\Controllers\Dashboard\ModelsController::class, 'edit_form' ] )->name( 'dashboard.models.edit' );

    Route::get( '/fonts', [ \App\Http\Controllers\Dashboard\FontsController::class, 'list_all' ] )->name( 'dashboard.fonts' );
    Route::get( '/fonts/new', [ \App\Http\Controllers\Dashboard\FontsController::class, 'create_form' ] )->name( 'dashboard.fonts.new' );
    Route::get( '/fonts/{font}/edit', [ \App\Http\Controllers\Dashboard\FontsController::class, 'edit_form' ] )->name( 'dashboard.fonts.edit' );

} );


Route::prefix( 'public' )->group( function() {

    Route::get( '/partners/connect/complete', [ \App\Http\Controllers\PublicControllers\PartnersController::class, 'complete' ] )
        ->name( 'public.partners.invite.complete' );

    Route::get( '/partners/connect/invite/thank-you', [ \App\Http\Controllers\PublicControllers\PartnersController::class, 'thank_you' ] )
        ->name( 'public.partners.invite.thank-you' );

    Route::get( '/partners/connect/{invite}', [ \App\Http\Controllers\PublicControllers\PartnersController::class, 'invite' ] )
        ->middleware( 'partner.invite' )
        ->name( 'public.partners.invite' );

    Route::get( '/integrations/shopify', [ \App\Http\Controllers\PublicControllers\IntegrationsController::class, 'shopify' ] );
    Route::get( '/integrations/shopify/activate', [ \App\Http\Controllers\PublicControllers\IntegrationsController::class, 'activate' ] )
        ->name( 'public.integrations.shopify.activate' );
    Route::get( '/integrations/shopify/manage', [ \App\Http\Controllers\PublicControllers\IntegrationsController::class, 'manage' ] )
        ->name( 'public.integrations.shopify.manage' );

} );

Route::prefix( 'webhooks/shopify/gdpr' )->middleware( 'webhook.shopify' )->group( function() {

    Route::post( '/customers/data_request', [ \App\Http\Controllers\Webhooks\ShopifyController::class, 'gdpr_request_data' ] );

    Route::post( '/customers/redact', [ \App\Http\Controllers\Webhooks\ShopifyController::class, 'gdpr_customers_redact' ] );

    Route::post( '/shop/redact', [ \App\Http\Controllers\Webhooks\ShopifyController::class, 'gdpr_shop_redact' ] );

} );

Route::prefix( 'webhooks/{partner}' )->group( function() {

    Route::prefix( 'shopify' )->middleware( 'webhook.shopify' )->group( function() {

        Route::post( '/products/create', [ \App\Http\Controllers\Webhooks\ShopifyController::class, 'products_create' ] )
            ->name( 'webhooks.shopify.products.create' );

        Route::post( '/products/update', [ \App\Http\Controllers\Webhooks\ShopifyController::class, 'products_update' ] )
            ->name( 'webhooks.shopify.products.update' );

        Route::post( '/products/delete', [ \App\Http\Controllers\Webhooks\ShopifyController::class, 'products_delete' ] )
            ->name( 'webhooks.shopify.products.delete' );

    } );

} );

Route::get( '/embed.js', [ \App\Http\Controllers\EmbedController::class, 'js_file' ] )->name( 'embed.js' );
Route::get( '/embed.css', [ \App\Http\Controllers\EmbedController::class, 'css_file' ] )->name( 'embed.css' );
Route::get( '/font_file/{slashData?}', [ \App\Http\Controllers\EmbedController::class, 'font_file' ] )->where( 'slashData', '(.*).otf' );

Route::prefix( 'embed' )->middleware( [ 'auth:sanctum', 'ability:app:embed', 'auth.partner' ] )->group( function() {

    Route::get( '/', [ \App\Http\Controllers\EmbedController::class, 'get_html' ] );

} );
