<?php

namespace Modules\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class UsuarioPermisos extends Model {
	protected $table = 'app_usuario_permisos';
	
	protected $fillable = ['usuario_id', 'ruta'];
	protected $hidden = ['created_at', 'updated_at'];

	public $incrementing = false;
	
	public function usuario(){
		// belongsTo = "pertenece a" | hace relacion desde el detalle hasta el maestro
		return $this->belongsTo('Modules\Admin\Model\Usuario', 'usuario_id');
	}
}
