<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {

            $table->id();

            $table->foreignId( 'partner_id' )->references( 'id' )->on( 'partners' );

            $table->string( 'name' );

            $table->integer( 'order' )->nullable( true );

            $table->integer( 'layer' )->nullable( true );

            $table->index( 'order' );

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
