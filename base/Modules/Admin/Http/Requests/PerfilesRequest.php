<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;

class PerfilesRequest extends Request {
	protected $reglasArr = [
		'nombre' => ['required', 'nombre', 'min:3', 'max:50'],
	];
}