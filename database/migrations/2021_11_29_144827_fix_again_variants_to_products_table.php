<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixAgainVariantsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists( 'product_variants' );

        Schema::table('products', function (Blueprint $table) {

            $table->foreignId( 'variant_for' )
                ->nullable()
                ->references( 'id' )
                ->on( 'products' );

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
