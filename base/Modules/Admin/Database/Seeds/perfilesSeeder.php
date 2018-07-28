<?php namespace Modules\Admin\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Admin\Model\Perfil;

class perfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perfiles = [
            'Desarrollador',
            'Administrador',
            'Tecnico',
            'Supervisor',
            'Asistente',
            'Secretaria',
            'Movil'
        ];

        foreach ($perfiles as $perfil) {
            Perfil::create([
                'nombre' => $perfil
            ]);
        }
    }
}
