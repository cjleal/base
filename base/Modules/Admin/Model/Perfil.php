<?php 

namespace Modules\Admin\Model;
use Modules\Admin\Model\Modelo;

class Perfil extends Modelo{
	protected $table = 'app_perfil';
	protected $fillable = ['nombre'];

	public function permisos(){
		return $this->hasMany('Modules\Admin\Model\PerfilesPermisos', 'perfil_id');
		
		// hasMany = "tiene muchas" | hace relacion desde el maestro hasta el detalle
		//return $this->hasMany('Modules\Admin\Model\App_usuario_permisos');
	}

	public function usuarios(){
		return $this->hasMany('Modules\Admin\Model\Usuario', 'perfil_id');
		
		// hasMany = "tiene muchas" | hace relacion desde el maestro hasta el detalle
		//return $this->hasMany('Modules\Admin\Model\App_usuario_permisos');
	}
}
