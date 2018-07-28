<?php

namespace Modules\Generator\Http\Requests;

use App\Http\Requests\Request;

class GeneratorRequest extends Request {
	protected $reglasArr = [
		'modulo'	=> ['required', 'min:3'],
		'tabla'		=> ['required', 'regex:/^[a-zA-Z_]+$/', 'min:3']
	];
}