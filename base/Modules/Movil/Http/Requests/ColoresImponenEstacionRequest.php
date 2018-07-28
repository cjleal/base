<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class ColoresImponenEstacionRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['required', 'min:1', 'max:10'], 
		'estaciones_id' => ['integer']
	];
}