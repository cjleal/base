<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColoresImponenEstacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
         Schema::create('colores_imponen_estacion', function(Blueprint $table){
            $table->increments('id');
            $table->string('descripcion', 10);
            $table->integer('estaciones_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('estaciones_id')
                ->references('id')->on('estaciones')
                ->onDelete('cascade')->onUpdate('cascade');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('colores_imponen_estacion');
    }
}
