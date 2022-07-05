<?php

use Illuminate\Database\Migrations\Migration;

class RegenerateApiTokenForPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach ( \App\Models\Partner::query()->get() as $partner )
        {

            /**
             * @var \App\Models\Partner $partner
             */

            $partner->tokens()->where( 'name', '=', 'app_token' )->delete();

            $partner->generateApiToken();
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
