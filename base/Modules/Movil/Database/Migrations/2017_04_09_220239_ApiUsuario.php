<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('api_usuario', function(Blueprint $table){
                $table->increments('id');
                
                $table->string('usuario', 50)->unique();
                $table->string('password', 60);
                $table->string('sexo', 1)->nullable();
                $table->integer('id_piel')->default(1);
                $table->string('correo', 50)->nullable()->unique();
                $table->string('telefono', 15)->nullable();
               
                $table->timestamps();
                $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_usuario');
    }
}
