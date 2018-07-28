<?php

namespace Modules\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class CatColores extends Model
{
   	protected $table = 'cat_colores';
	protected $fillable = ['descripcion'];

	protected $hidden = ['remember_token', 'created_at', 'updated_at'];

	public $permisos = [];
}
