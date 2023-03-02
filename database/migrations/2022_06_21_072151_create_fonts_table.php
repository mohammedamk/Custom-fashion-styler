<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFontsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fonts', function (Blueprint $table) {
            $table->id();

            $table->string('family', 100);

            // $table->boolean( 'is_variable_font' )->default( false );
            // Weights

            // $table->string('thin_100', 100);
            // $table->string('extralight_200', 100);
            // $table->string('light_300', 100);
            // $table->string('regular_400', 100);
            // $table->string('medium_500', 100);
            // $table->string('semibold_600', 100);
            // $table->string('bold_700', 100);
            // $table->string('extrabold_800', 100);
            // $table->string('black_900', 100);

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
        Schema::dropIfExists('fonts');
    }
}
