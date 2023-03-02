<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_integrations', function (Blueprint $table) {

            $table->id();

            $table->string( 'shopify_url' );

            $table->string( 'access_token' )->nullable( true );
            $table->string( 'refresh_token' )->nullable( true );
            $table->timestamp( 'expire_at' )->nullable( true )->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopify_integrations');
    }
}
