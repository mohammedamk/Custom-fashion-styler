<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {

            $table->id();

            $table->string( 'name', 60 );

            $table->string( 'email' )->unique();

            $table->string( 'primary_color', 6 )->nullable( true );
            $table->string( 'secondary_color', 6 )->nullable( true );

            $table->foreignId( 'user_id' )
                ->nullable( true )
                ->references( 'id' )
                ->on( 'users' );

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner');
    }
}
