<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class TonoPiel extends modelo
{
    protected $table = 'tono_piel';
    protected $fillable = ["descripcion"];
    protected $campos = [
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion del Tono Piel'
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}