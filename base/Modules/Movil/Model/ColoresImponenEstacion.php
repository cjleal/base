<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class ColoresImponenEstacion extends modelo
{
    protected $table = 'colores_imponen_estacion';
    protected $fillable = ["descripcion","estaciones_id"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion del Colores Imponen Estacion'
    ],
    'estaciones_id' => [
        'type' => 'text',
        'label' => 'Estaciones',
        'placeholder' => 'Estaciones del Colores Imponen Estacion'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}