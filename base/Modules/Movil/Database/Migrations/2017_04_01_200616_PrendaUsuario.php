<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrendaUsuario extends Migration
{
    public function up(){
        Schema::create('prenda_usuario', function(Blueprint $table){
            $table->increments('id');
            
            $table->integer('usuario_id')->unsigned()->nullable();
            $table->string('url', 100)->nullable();
            $table->boolean('favorito');
            $table->integer('tipo_prenda_id')->unsigned()->nullable();
            $table->integer('ocasiones_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            /*$table->foreign('api_usuario_id')
                ->references('id')->on('api_usuario')
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
        Schema::dropIfExists('prenda_usuario');
    }
}
