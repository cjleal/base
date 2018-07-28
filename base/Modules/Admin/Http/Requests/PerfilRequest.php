<?php namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;

class PerfilRequest extends Request {
	protected $reglasArr = [
		'dni'            => ['required', 'integer', 'unique:app_usuario,dni'],
		'nombre'         => ['required', 'nombre', 'min:3', 'max:50'],
		'apellido'       => ['nombre', 'min:3', 'max:100'],
		'correo'         => ['max:50', 'unique:app_usuario,correo'],
		'telefono'       => ['telefono', 'min:3', 'max:15'],
		'sexo'           => ['max:1'],
		'edo_civil'      => ['max:2'],
		'direccion'      => ['max:200'],
		'facebook'       => ['max:200'],
		'instagram'      => ['max:200'],
		'twitter'        => ['max:200']
	];
}
