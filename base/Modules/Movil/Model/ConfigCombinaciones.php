<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;



class ConfigCombinaciones extends modelo
{
    protected $table = 'config_combinaciones';
    protected $fillable = ["prenda_princ_id","descripcion"];
    protected $campos = [
    'prenda_princ_id' => [
        'type' => 'select',
        'label' => 'Prenda Principal',
        'placeholder' => ''
    ],
    'descripcion' => [
        'type' => 'text',
        'label' => 'Descripcion',
        'placeholder' => 'Descripcion'
        
    ]
];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
    }

    
}