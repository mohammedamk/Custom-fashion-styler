<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrivateAppTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table( 'shopify_integrations', function( Blueprint $table ) {

            $table->string( 'private_app_key' )->nullable( true );
            $table->string( 'private_app_secret' )->nullable( true );

        } );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table( 'shopify_integrations', function( Blueprint $table ) {

            $table->dropColumn( 'private_app_key' );
            $table->dropColumn( 'private_app_secret' );

        } );
    }
}
