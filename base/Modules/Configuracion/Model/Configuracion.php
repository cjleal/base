<?php
namespace Modules\Configuracion\Model;

use Modules\Admin\Model\modelo;

class Configuracion extends modelo{
	protected $table = 'configuracion';
    protected $fillable = ["propiedad", "valor"];

    static protected $conf = [];

    static function get($propiedad){
    	$propiedad = strtolower($propiedad);
    	if (empty(self::$conf)){
    		$rs = self::all();
    		foreach ($rs as $fila) {
    			self::$conf[strtolower($fila->propiedad)] = $fila->valor;
    		}
    	}

    	return isset(self::$conf[$propiedad]) ? self::$conf[$propiedad] : '';
    }
}