<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class TelasRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['min:3', 'max:100'], 
		'estacion_id' => ['integer'], 
		'url' => ['min:3', 'max:100']
	];
}