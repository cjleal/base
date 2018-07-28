<?php

namespace Modules\Movil\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Movil\Model\Estaciones;
class EstacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
      
       $estaciones = [
       	 	['descripcion' => 'Verano', 'estatus'=> "1"],
       	 	['descripcion' => 'Invierno', 'estatus'=> "0"],
       	 	['descripcion' => 'Primavera', 'estatus'=> "0"],
       	 	['descripcion' => 'Otoño', 'estatus'=> "0"],
        ];

        foreach ($estaciones as $estacion) {
            estaciones::create([
                'descripcion' => $estacion['descripcion'],
                'estatus' => $estacion['estatus']
            ]);
        }
     //
    }
}

