<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppUsuarioPermisos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_usuario_permisos', function(Blueprint $table){
            $table->integer('usuario_id')->unsigned();
            $table->string('ruta', 200);

            $table->timestamps();
            //$table->softDeletes();
            
            $table->foreign('usuario_id')
                ->references('id')->on('app_usuario')
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
        Schema::dropIfExists('app_usuario_permisos');
    }
}
