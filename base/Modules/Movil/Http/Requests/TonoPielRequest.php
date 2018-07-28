<?php

namespace Modules\Movil\Http\Requests;

use App\Http\Requests\Request;

class TonoPielRequest extends Request {
    protected $reglasArr = [
		'descripcion' => ['min:3', 'max:100']
	];
}