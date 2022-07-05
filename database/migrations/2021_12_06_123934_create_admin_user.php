<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table( 'users', function( Blueprint $table ) {

            $table->boolean( 'is_admin' )->default( 0 );

        } );

        $user = new \App\Models\User( [

            'name'              => 'James',
            'email'             => 'james@moda-match.com',
            'email_verified_at' => \Carbon\Carbon::now(),
            'password'          => \Illuminate\Support\Facades\Hash::make( 'Montreal2021@!' ),
            'is_admin'          => true

        ] );

        $user->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table( 'users', function( Blueprint $table ) {

            $table->dropColumn( 'is_admin' );

        } );

    }
}
