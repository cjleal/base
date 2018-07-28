<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PreguntasOcasion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_preguntas_ocasiones', function (Blueprint $table) {
            $table->string('descripcion', 100);
            $table->integer('ocasiones_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('ocasiones_id')
                ->references('id')->on('ocasiones')
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
        Schema::dropIfExists('api_preguntas_ocasiones');
    }
}
