<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class Telas extends modelo
{
    protected $table = 'telas';
    protected $fillable = ["descripcion","estacion_id","url"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion de la Tela'
    ],
    'estacion_id' => [
        'type' => 'select',
        'label' => 'Estacion',
        'placeholder' => 'Estacion del Telas'
    ],
    'url' => [
        'type' => 'text',
        'label' => 'Url',
        'placeholder' => 'Url del Telas'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}