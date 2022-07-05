<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_partner', function (Blueprint $table) {

            $table->foreignId( 'model_id' )->references( 'id' )->on( 'models' );
            $table->foreignId( 'partner_id' )->references( 'id' )->on( 'partners' );

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_partner');
    }
}
