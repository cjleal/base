<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class TipoPrendaDetalleRequest extends Request {
    protected $reglasArr = [
		'tipo_prenda_id' => ['integer'], 
		'descripcion' => ['min:3', 'max:255']
	];
}