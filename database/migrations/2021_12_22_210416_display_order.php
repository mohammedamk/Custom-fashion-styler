<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DisplayOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'products', function( Blueprint $table ) {

            $table->string( 'group_id' )->nullable()->index();

        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'products', function( Blueprint $table ) {

            $table->dropColumn( 'group_id' );

        } );
    }
}
