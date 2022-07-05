<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware( [ 'auth:sanctum', 'can:admin' ] )->group( function() {

    Route::get( '/partners', [ \App\Http\Controllers\API\PartnersController::class, 'list_partners' ] )
        ->name( 'api.partners.list' );

    Route::post( '/partners', [ \App\Http\Controllers\API\PartnersController::class, 'create_partner' ] )
        ->name( 'api.partners.create' );

    Route::put( '/partners/{partner}', [ \App\Http\Controllers\API\PartnersController::class, 'edit_partner' ] )
        ->name( 'api.partners.edit' );

    Route::post( '/partners/{partner}/invite', [ \App\Http\Controllers\API\PartnersController::class, 'invite' ] )
        ->name( 'api.partners.integrations.invite' );

    Route::post( '/partners/{partner}/import', [ \App\Http\Controllers\API\PartnersController::class, 'import' ] )
        ->name( 'api.partners.import' );

    Route::put( '/partners/{partner}/models', [ \App\Http\Controllers\API\PartnersController::class, 'assign_models' ] )
        ->name( 'api.partners.models' );


    Route::get( '/partners/{partner}/products', [ \App\Http\Controllers\API\ProductsController::class, 'list_products' ] )
        ->name( 'api.partners.products.list' );

    Route::get( '/partners/{partner}/categories', [ \App\Http\Controllers\API\ProductsController::class, 'list_categories' ] )
        ->name( 'api.partners.categories.list' );

    Route::put( '/partners/{partner}/categories/map', [ \App\Http\Controllers\API\ProductsController::class, 'map_categories' ] )
        ->name( 'api.partners.categories.map' );

    Route::get( '/partners/{partner}/import/{job_id}/status', [ \App\Http\Controllers\API\PartnersController::class, 'import_status' ] )
        ->name( 'api.partners.import.status' );



    Route::put( '/products/{product}/image', [ \App\Http\Controllers\API\ProductsController::class, 'attach_image_to_product' ] )
        ->name( 'api.products.image' );

    Route::delete( '/products/{product}/image', [ \App\Http\Controllers\API\ProductsController::class, 'detach_image_from_product' ] )
        ->name( 'api.products.image' );

    Route::put( '/products/{product}/galleryimage', [ \App\Http\Controllers\API\ProductsController::class, 'attach_galleryimage_to_product' ] )
        ->name( 'api.products.galleryimage' );
    Route::delete( '/products/{product}/galleryimage', [ \App\Http\Controllers\API\ProductsController::class, 'detach_galleryimage_from_product' ] )
        ->name( 'api.products.galleryimage' );

    Route::get( '/models', [ \App\Http\Controllers\API\ModelsController::class, 'list_models' ] )
        ->name( 'api.models.list' );

    Route::post( '/models', [ \App\Http\Controllers\API\ModelsController::class, 'create_model' ] )
        ->name( 'api.models.create' );

    Route::put( '/models/{model}', [ \App\Http\Controllers\API\ModelsController::class, 'edit_model' ] )
        ->name( 'api.models.edit' );

} );


Route::prefix( 'public' )->group( function() {

    Route::get( '/partners/connect/{invite}/shopify', [ \App\Http\Controllers\API\PublicControllers\Integrations\ShopifyController::class, 'connect' ] )
        ->middleware( 'partner.invite' )
        ->name( 'public.api.partners.integrations.shopify.connect' );

} );


Route::prefix( 'app' )->middleware( [ 'auth:sanctum', 'auth.partner' ] )->group( function() {

    Route::get( 'models', [ \App\Http\Controllers\API\Client\ModelsController::class, 'retrieveModels' ] )
        ->middleware( 'ability:app:models.retrieve' );
    Route::get( 'models/{model_id}', [ \App\Http\Controllers\API\Client\ModelsController::class, 'retrieveModel' ] )
        ->middleware( 'ability:app:models.retrieve' );

    Route::get( 'products', [ \App\Http\Controllers\API\Client\ProductsController::class, 'retrieveProducts' ] )
        ->middleware( 'ability:app:products.retrieve' );
    Route::get( 'products/from-source/{source_id}', [ \App\Http\Controllers\API\Client\ProductsController::class, 'retrieveProductFromSource' ] )
        ->middleware( 'ability:app:products.retrieve' );
    Route::get( 'products/{product_id}', [ \App\Http\Controllers\API\Client\ProductsController::class, 'retrieveProduct' ] )
        ->middleware( 'ability:app:products.retrieve' );
    Route::get( 'categories/{category}/products', [ \App\Http\Controllers\API\Client\ProductsController::class, 'retrieveProducts' ] )
        ->middleware( 'ability:app:products.retrieve' );

    Route::get( 'categories', [ \App\Http\Controllers\API\Client\ProductsController::class, 'retrieveCategories' ] )
        ->middleware( 'ability:app:categories.retrieve' );

    Route::post( 'checkout', [ \App\Http\Controllers\API\Client\CartController::class, 'checkout' ] )
        ->middleware( 'ability:app:cart.manage' );

} );

Route::post( 'ordercreation', [ \App\Http\Controllers\API\Client\CartController::class, 'orderCreation' ] );
