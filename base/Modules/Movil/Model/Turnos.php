<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class Turnos extends modelo
{
    protected $table = 'turnos';
    protected $fillable = ["descripcion"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion del Turnos'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}