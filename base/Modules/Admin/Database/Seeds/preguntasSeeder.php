<?php namespace Modules\Admin\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Admin\Model\Preguntas;

class preguntasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	$preguntas = [
			'Lugar de nacimiento de mi madre',
			'Nombre de mi Primera Mascota',
			'Nombre de mi Primera Novia',
			'Segundo Nombre de mi madre',
			'Color favorito'
			
		];
		foreach ($preguntas as $preguntas) {
            Preguntas::create([
                'descripcion' => $preguntas
            ]);
        }
    }
}
