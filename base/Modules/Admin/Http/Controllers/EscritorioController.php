<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Admin\Http\Controllers\Controller;

class EscritorioController extends Controller {
	public $autenticar = false;

	protected $titulo = 'Escritorio';

	public function __construct() {
		parent::__construct();

		$this->middleware('Authenticate');
	}

	public function index() {
		$permisos = [
			'ingresos/graficas/inventario',
			'proyectos/escritorio',
			'empleados',
			'carnetizacion'
		];

		$pase = 0;
		$ultimoPermiso = '';

		foreach ($permisos as $permiso) {
			if ($this->permisologia($permiso)) {
				$pase++;
				$ultimoPermiso = $permiso;
			}
		}

		if ($pase > 1){
			return $this->view('admin::Escritorio');
		} else {
			return Redirect($ultimoPermiso);
		}
		return $this->view('admin::Escritorio');
		//return Redirect('ingresos/graficas/inventario');
	}
}