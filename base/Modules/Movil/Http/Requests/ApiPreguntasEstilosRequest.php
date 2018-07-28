<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class ApiPreguntasEstilosRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['required', 'min:3', 'max:100'], 
		'estilo_id' => ['integer']
	];
}