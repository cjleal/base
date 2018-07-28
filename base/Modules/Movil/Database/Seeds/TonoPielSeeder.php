<?php

namespace Modules\Movil\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Movil\Model\TonoPiel;
class TonoPielSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tonopiel = [
       	 	['descripcion' => 'Blanca'],
       	 	['descripcion' => 'Morena']
        ];

        foreach ($tonopiel as $tono) {
            TonoPiel::create([
                'descripcion' => $tono['descripcion']
            ]);
        }
    }
}
