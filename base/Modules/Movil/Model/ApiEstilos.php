<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class ApiEstilos extends modelo
{
    protected $table = 'api_estilos';
    protected $fillable = ["nombre","descripcion"];
    protected $campos = [
    'nombre' => [
        'type' => 'text',
        'label' => 'Nombre',
        'placeholder' => 'Nombre del Estilos'
    ],
    'descripcion' => [
        'type' => 'textarea',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion del Estilos',
        'cont_class' => 'col-sm-12'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}