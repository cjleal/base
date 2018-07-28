<?php

namespace Modules\Movil\Model;

use Modules\Admin\Model\Modelo;

class Imagenes extends modelo{
    protected $table = 'telas_img';
    protected $fillable = ['telas_id','nombre'];

	public function telas(){
		return $this->belongsTo('Modules\Movil\Model\Telas');

	}
}