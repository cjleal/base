<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppPerfilesPermisos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_perfiles_permisos', function(Blueprint $table){
            $table->integer('perfil_id')->unsigned();
            $table->string('ruta', 200);

            $table->timestamps();
            //$table->softDeletes();
            
            $table->foreign('perfil_id')
                ->references('id')->on('app_perfil')
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
        Schema::dropIfExists('app_perfiles_permisos');
    }
}
