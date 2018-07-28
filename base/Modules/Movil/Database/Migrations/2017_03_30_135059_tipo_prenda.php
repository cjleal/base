<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TipoPrenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_prenda', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion', 100);
            $table->softDeletes();
            $table->timestamps();
            /*$table->foreign('ocasiones_id')
                ->references('id')->on('ocasiones')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('estaciones_id')
                ->references('id')->on('estaciones')
                ->onDelete('cascade')->onUpdate('cascade');
             */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_prenda');
    }
}
