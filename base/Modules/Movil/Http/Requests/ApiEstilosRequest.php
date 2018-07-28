<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class ApiEstilosRequest extends Request {
    protected $reglasArr = [
		'nombre' => ['required', 'min:3', 'max:100'], 
		'descripcion' => ['min:3', 'max:900']
	];
}