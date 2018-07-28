<?php

namespace Modules\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class Preguntas extends Model
{
    protected $table = 'app_preguntas';
	protected $fillable = ['descripcion'];

	protected $hidden = ['remember_token', 'created_at', 'updated_at'];

	public $permisos = [];
}
