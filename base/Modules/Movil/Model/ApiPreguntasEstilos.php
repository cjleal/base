<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class ApiPreguntasEstilos extends modelo
{
    protected $table = 'api_preguntas_estilos';
    protected $fillable = ["descripcion","estilo_id"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Pregunta'
    ],
    'estilo_id' => [
        'type' => 'select',
        'label' => 'Estilo',
        'placeholder' => 'Estilo'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}