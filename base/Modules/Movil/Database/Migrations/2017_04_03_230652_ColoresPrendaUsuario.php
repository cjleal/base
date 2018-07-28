<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColoresPrendaUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('color_prenda_usuario', function(Blueprint $table){
            $table->integer('prenda_usuario_id')->unsigned()->nullable();
            $table->integer('colores_hexa_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('prenda_usuario_id')
                ->references('id')->on('prenda_usuario')
                ->onDelete('cascade')->onUpdate('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
      Schema::dropIfExists('color_prenda_usuario');
    }
}
