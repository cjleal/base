<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class EstacionesRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['required', 'min:3', 'max:100'], 
		'estatus' => ['min:3', 'max:1']
	];
}