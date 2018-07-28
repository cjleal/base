<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_historico', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('tabla', 50);
            $table->string('concepto', 50);
            $table->string('idregistro', 50);
            $table->string('usuario', 50);

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
        Schema::dropIfExists('app_historico');
    }
}
