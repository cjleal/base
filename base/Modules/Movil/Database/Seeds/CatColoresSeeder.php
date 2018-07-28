<?php

namespace Modules\Movil\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Movil\Model\CatColores;
class CatColoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colores =  [
            ['descripcion' => 'Azul'],
            ['descripcion' => 'Amarillo'],
            ['descripcion' => 'Anaranjado'],
        ];
        foreach ($colores as $color) {
            $CatColores = CatColores::create([
                'descripcion' => $color["descripcion"]
            ]);
        }
        
    }
}
