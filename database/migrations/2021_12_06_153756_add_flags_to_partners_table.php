<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlagsToPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {

            $table->boolean( 'is_rotate_enabled' )->default( false );
            $table->boolean( 'is_tuck_in_enabled' )->default( false );

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {

            $table->dropColumn( 'is_rotate_enabled' );
            $table->dropColumn( 'is_tuck_in_enabled' );

        });
    }
}
