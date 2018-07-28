<?php namespace Modules\Admin\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Admin\Model\Usuario;

class usuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = Usuario::create([
			'usuario' => 'admin',
			'nombre' => 'Administrador',
			'apellido'=> '',
			'id_piel' => 1,
			'password' => 'admin',
			'dni' => 12345678,
			'correo' => 'admin@gmail.com',
			'telefono' => '0414-123-1234',
			'autenticacion' => 'B',
			'perfil_id' => 1,
			'super' => 's',
			'sexo'=> 'm',
			'edo_civil'=> 's',
			'preguntas_pri_id'=> 1,
			'preguntas_seg_id'=> 2,
			'respuesta_pri'=> 'mathias',	
			'respuesta_seg'=> 'leal'	

		]);
    }
}
