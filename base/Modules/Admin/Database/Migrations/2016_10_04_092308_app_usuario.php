<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_usuario', function(Blueprint $table){
            $table->increments('id');
            
            $table->string('usuario', 50)->unique();
            $table->string('password', 60);

            $table->integer('dni')->unsigned()->unique();
            $table->string('nombre', 50);
            $table->string('apellido', 100)->nullable();
            $table->integer('id_piel')->default(1);;
            $table->string('correo', 50)->nullable()->unique();
            $table->string('telefono', 15)->nullable();
            $table->string('foto')->default('user.png');
            $table->integer('perfil_id')->unsigned()->nullable();

            $table->char('autenticacion', 1)->default('l');

            $table->char('super', 1)->default('n');

            $table->string('sexo', 1)->nullable();
            $table->string('edo_civil',2)->nullable();  
            $table->string('direccion', 200)->nullable();    
            $table->string('facebook', 200)->nullable(); 
            $table->string('instagram', 200)->nullable();    
            $table->string('twitter', 200)->nullable();
            $table->integer('preguntas_pri_id')->unsigned()->nullable();
            $table->integer('preguntas_seg_id')->unsigned()->nullable();
            $table->string('respuesta_pri', 200)->nullable();
            $table->string('respuesta_seg', 200)->nullable();
            $table->rememberToken();
            
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('app_usuario');
    }
}
