<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariantsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'products', function (Blueprint $table) {

            $table->json( 'color_options' )->nullable();
            $table->json( 'size_options' )->nullable();
            $table->json( 'material_options' )->nullable();
            $table->json( 'style_options' )->nullable();

        } );

        Schema::create( 'product_variants', function (Blueprint $table) {

            $table->id();

            $table->string( 'title' );

            $table->foreignId( 'product_id' )->references( 'id' )->on( 'products' );

            $table->float( 'price', 8, 2 );

            $table->string( 'color' )->nullable();
            $table->string( 'size' )->nullable();
            $table->string( 'material' )->nullable();
            $table->string( 'style' )->nullable();

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
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn( 'color_options' );
            $table->dropColumn( 'size_options' );
            $table->dropColumn( 'material_options' );
            $table->dropColumn( 'style_options' );

        });

        Schema::dropIfExists( 'product_variants' );
    }
}
