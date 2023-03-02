<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupportForWebhooksToShopifyIntegration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopify_integrations', function (Blueprint $table) {

            $table->string( 'create_webhook_id' )->nullable();
            $table->string( 'update_webhook_id' )->nullable();
            $table->string( 'delete_webhook_id' )->nullable();

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

            $table->dropColumn( 'create_webhook_id' );
            $table->dropColumn( 'update_webhook_id' );
            $table->dropColumn( 'delete_webhook_id' );

        });
    }
}
