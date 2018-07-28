<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class CatColores extends modelo
{
    protected $table = 'cat_colores';
    protected $fillable = ["descripcion"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripción de Categorias de Colores'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}