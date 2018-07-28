<?php

namespace Modules\Admin\Http\Requests;

use App\Http\Requests\Request;
 
class AppUsuarioRequest extends Request {
	protected $tabla = 'app_usuario';
	protected $reglasArr = [
		'usuario' => ['required', 'min:3', 'max:50'], 
		'password' => ['required', 'min:3', 'max:60'], 
		'nombre' => ['required', 'min:3', 'max:50'], 
		'apellido' => ['required', 'min:3', 'max:100'], 
		'correo' => ['required', 'min:3', 'max:50'], 
		'telefono' => ['required', 'min:3', 'max:15'], 
		'foto' => ['required', 'min:3', 'max:255'], 
		'app_perfil_id' => ['required', 'integer'], 
		'autenticacion' => ['required', 'min:3', 'max:1'], 
		'super' => ['required', 'min:3', 'max:1'], 
		'sexo' => ['required', 'min:3', 'max:1'], 
		'edo_civil' => ['required', 'min:3', 'max:2'], 
		'direccion' => ['required', 'min:3', 'max:250'], 
		'facebook' => ['required', 'min:3', 'max:250'], 
		'instagram' => ['required', 'min:3', 'max:250'], 
		'twitter' => ['required', 'min:3', 'max:250'], 
		'remember_token' => ['required', 'min:3', 'max:100']
	];

	public function rules(){
		return $this->reglas();
	}
}