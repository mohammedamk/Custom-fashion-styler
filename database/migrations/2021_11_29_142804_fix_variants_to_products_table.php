<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixVariantsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn( 'color_options' );
            $table->dropColumn( 'size_options' );
            $table->dropColumn( 'material_options' );
            $table->dropColumn( 'style_options' );

            $table->json( 'option1_choices' )->nullable();
            $table->json( 'option2_choices' )->nullable();
            $table->json( 'option3_choices' )->nullable();
            $table->json( 'option4_choices' )->nullable();

        });


        Schema::table( 'product_variants', function (Blueprint $table) {

            $table->dropColumn( 'color' );
            $table->dropColumn( 'size' );
            $table->dropColumn( 'material' );
            $table->dropColumn( 'style' );

            $table->string( 'option1' )->nullable();
            $table->string( 'option2' )->nullable();
            $table->string( 'option3' )->nullable();
            $table->string( 'option4' )->nullable();


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
            //
        });
    }
}
