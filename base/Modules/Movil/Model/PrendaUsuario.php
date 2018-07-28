<?php

namespace Modules\Movil\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
class PrendaUsuario extends Modelo implements AuthenticatableContract, CanResetPasswordContract {
	use Authenticatable, CanResetPassword;
	
	protected $table = 'prenda_usuario';
	protected $fillable = [
		'id_usuario', 
		'id_tipo_prenda',
		'url', 
		'favorito' 
		
		
	];

	protected $hidden = ['remember_token', 'created_at', 'updated_at'];

	public $permisos = [];

}
