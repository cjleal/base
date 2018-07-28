<?php 

namespace Modules\Movil\Model;


use Modules\Admin\Model\Modelo;

class ApiUsuario extends Modelo  {
	
	protected $table = 'api_usuario';
	protected $fillable = [
		'usuario', 
		'password',
		'nombre', 
		'tono_piel',
		'correo', 
		'telefono' 
	];

	protected $hidden = ['password', 'created_at', 'updated_at'];

	public $permisos = [];
	/*
	public function setPasswordAttribute($value){
        $this->attributes['password'] = \Hash::make($value);
    }

    public function setUsuarioAttribute($value){
        $this->attributes['usuario'] = strtolower($value);
    }

    public function setCorreoAttribute($value){
        $this->attributes['correo'] = strtolower($value);
    }
    */
}
