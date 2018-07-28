<?php

namespace Modules\Movil\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Movil\Model\Ocasiones;
class OcasionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $ocasiones = [
       	 	['descripcion' => 'Deportivo'],
       	 	['descripcion' => 'Fiesta'],
       	 	['descripcion' => 'Gala'],
       	 	['descripcion' => 'Cine'],
        ];

        foreach ($ocasiones as $ocasion) {
            ocasiones::create([
                'descripcion' => $ocasion['descripcion']
            ]);
        }
    }
}
