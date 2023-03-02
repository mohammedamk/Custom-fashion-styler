<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHostColumnToIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopify_integrations', function (Blueprint $table) {

            $table->string( 'shopify_host' )->nullable( true );

            $table->index( 'shopify_host' );

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shopify_integrations', function (Blueprint $table) {

            $table->dropColumn( 'shopify_host' );

        });
    }
}
