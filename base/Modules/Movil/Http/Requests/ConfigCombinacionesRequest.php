<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class ConfigCombinacionesRequest extends Request {
    protected $reglasArr = [
		//'prenda_princ_id' => ['integer', 'min:3', 'max:1'], 
		'descripcion' => ['min:3', 'max:255'], 
		//'sexo' => ['min:3', 'max:1'], 
		'r' => ['integer'], 
		'g' => ['integer'], 
		'b' => ['integer']
	];
}