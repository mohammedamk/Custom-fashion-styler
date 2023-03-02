<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_invites', function (Blueprint $table) {

            $table->uuid( 'id' )->primary();

            $table->foreignId( 'partner_id' )->references( 'id' )->on( 'partners' );

            $table->string( 'secret_key', 64 );

            $table->timestamp( 'expire_at' )->index();

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
        Schema::dropIfExists('partner_invites');
    }
}
