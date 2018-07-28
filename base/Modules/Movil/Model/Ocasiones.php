<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class Ocasiones extends modelo
{
    protected $table = 'ocasiones';
    protected $fillable = ["descripcion"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion del Ocasiones'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}