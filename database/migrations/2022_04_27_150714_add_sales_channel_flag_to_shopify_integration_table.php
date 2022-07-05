<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalesChannelFlagToShopifyIntegrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopify_integrations', function (Blueprint $table) {

            $table->boolean( 'is_sales_channel_installed' )->default( 0 );

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

            $table->dropColumn( 'is_sales_channel_installed' );

        });
    }
}
