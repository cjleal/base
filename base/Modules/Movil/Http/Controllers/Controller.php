<?php namespace Modules\Movil\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;

class Controller  extends BaseController {
	public $app = 'movil';

	protected $patch_js = [
		'public/js',
		'public/plugins',
		'Modules/Movil/Assets/js',
	];

	protected $patch_css = [
		'public/css',
		'public/plugins',
		'Modules/Movil/Assets/css',
	];
}