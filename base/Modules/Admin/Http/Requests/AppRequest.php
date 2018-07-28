<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;
 
class AppRequest extends Request {
	protected $tabla = 'app';
	protected $reglasArr = [
		
	];

	public function rules(){
		$rules = parent::rules();
		
		return $rules;
	}
}