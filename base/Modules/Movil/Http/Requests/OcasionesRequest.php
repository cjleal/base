<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class OcasionesRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['required', 'min:3', 'max:100']
	];
}