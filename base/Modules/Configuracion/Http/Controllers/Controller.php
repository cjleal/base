<?php namespace Modules\Configuracion\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController {
	public $app = 'admin';

	protected $patch_js = [
		'public/js',
		'public/plugins',
		'Modules/Configuracion/Assets/js',
	];

	protected $patch_css = [
		'public/css',
		'public/plugins',
		'Modules/Configuracion/Assets/css',
	];
}