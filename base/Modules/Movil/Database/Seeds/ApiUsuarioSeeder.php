<?php

namespace Modules\Movil\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Movil\Model\ApiUsuario;
class ApiUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	$usuario = ApiUsuario::create([
			'usuario' => 'prueba',
			'password' => bcrypt('prueba'),
			'id_piel' => 1,
			'correo' => 'prueba@gmail.com',
			'telefono' => '0414-123-1234'
		]);

    }
}
