<?php

namespace Modules\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class PerfilesPermisos extends Model{
	protected $table = 'app_perfiles_permisos';
	protected $fillable = ['perfil_id', 'ruta'];

	protected $primaryKey = null;
    public $incrementing = false;

	protected $hidden = ['created_at', 'updated_at'];

	public function perfil(){
		// belongsTo = "pertenece a" | hace relacion desde el detalle hasta el maestro
		return $this->belongsTo('Modules\Admin\Model\Perfil', 'perfil_id');
	}
}
