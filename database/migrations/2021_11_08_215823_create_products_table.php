<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string( 'title' );
            $table->text( 'description' )->nullable( true );

            $table->foreignId( 'partner_id' )->references( 'id' )->on( 'partners' );

            $table->float( 'price', 8, 2 );

            $table->string( 'source_product_type' )->nullable( true );
            $table->string( 'source_product_id' )->index();

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
        Schema::dropIfExists('products');
    }
}
