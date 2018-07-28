<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;
use DB;
class TipoPrenda extends modelo
{
    protected $table = 'tipo_prenda';
    protected $fillable = ["descripcion"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion del Tipo Prenda'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        //$Ocasiones = DB::table('ocasiones')->select('id', 'descripcion')->get();
        /*
        $opciones = [];
        foreach ($Ocasiones as $key => $value) {
            $opciones[] = $value;
        }
        *//**/
        
    }

    
}