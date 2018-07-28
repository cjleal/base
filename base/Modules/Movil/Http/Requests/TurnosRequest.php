<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class TurnosRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['min:3', 'max:50']
	];
}