<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GenerateApiTokenForPartnersNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table( 'partners', function( Blueprint $table ) {

            $table->string( 'api_token' )->nullable( true );

        } );

        foreach ( \App\Models\Partner::all() as $partner )
        {

            /**
             * @var \App\Models\Partner $partner
             */

            $token = $partner->createToken( 'app_token' );

            $partner->setAttribute( 'api_token', $token->plainTextToken );

            $partner->save();
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
