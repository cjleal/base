<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class TipoPrendaRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['required', 'min:3', 'max:100'], 
		'ocasiones_id' => ['integer'], 
		'estaciones_id' => ['integer']
	];
}