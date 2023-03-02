<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConnectShopifyWebhooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach( \App\Models\ShopifyIntegration::query()->get() as $integration )
        {

            /**
             * @var \App\Models\ShopifyIntegration $integration
             */

            $integration->createWebhooks();

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
