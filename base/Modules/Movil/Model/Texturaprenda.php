<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class Texturaprenda extends modelo
{
    protected $table = 'texturaprenda';
    protected $fillable = ["descripcion"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion de Textura'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}