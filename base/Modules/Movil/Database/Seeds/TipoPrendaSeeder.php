<?php

namespace Modules\Movil\Database\Seeds;

use Illuminate\Database\Seeder;
use Modules\Movil\Model\TipoPrenda;

class TipoPrendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        TipoPrenda::create([
            'descripcion' => 'TOPS']);
    }
}
