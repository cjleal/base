<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class Estaciones extends modelo
{
    protected $table = 'estaciones';
    protected $fillable = ["descripcion","estatus"];
    protected $campos = [
        'descripcion' => [
            'type'        => 'text',
            'label'       => 'Descripcion',
            'placeholder' => 'Descripcion del Estaciones'
        ],
        'estatus' => [
            'type'        => 'select',
            'label'       => 'Estatus',
            'placeholder' => 'Estatus del Estaciones'
        ]
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->campos['estatus']['options'] = [0 => 'Inactivo', 1 => 'Activo'];
    }

    
}