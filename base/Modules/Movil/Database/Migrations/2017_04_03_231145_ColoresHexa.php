<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColoresHexa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('colores_hexa', function(Blueprint $table){
            $table->increments('id');
            $table->integer('cat_colores_id')->unsigned()->nullable();
            $table->string('descripcion', 100)->nullable(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cat_colores_id')
                ->references('id')->on('cat_colores')
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
       Schema::dropIfExists('colores_hexa');
    }
}
