<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class TipoPrendaDetalle extends modelo
{
    protected $table = 'tipo_prenda_detalle';
    protected $fillable = ["tipo_prenda_id","descripcion"];
    protected $campos = [
    'tipo_prenda_id' => [
        'type' => 'select',
        'label' => 'Tipo Prenda',
        'placeholder' => 'Categor\u00eda de la Prenda'
    ],
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion  de la Prenda'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}