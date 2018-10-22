<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosOportunidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_oportunidad', function (Blueprint $table) {

            $table->increments('id_evento_oportunidad')->unsigned();

            $table->uuid('id_oportunidad');
            $table->foreign('id_oportunidad')->references('id_oportunidad')->on('oportunidades')->onDelete('cascade');

             $table->uuid('id_colaborador');
            $table->foreign('id_colaborador')->references('id')->on('users')->onDelete('cascade');

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
         Schema::dropIfExists('eventos_oportunidad');

    }
}
