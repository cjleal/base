<?php

namespace Modules\Admin\Http\Controllers;

//Dependencias
use DB;
use Yajra\Datatables\Datatables;

//Controlador Padre
use Modules\Admin\Http\Controllers\Controller;

//Request
use Modules\Admin\Http\Requests\PerfilesRequest;
use App\Http\Requests\Request;

//modelos
use Modules\Admin\Model\Perfil;
use Modules\Admin\Model\PerfilesPermisos;
use Modules\Admin\Model\Menu;

class PerfilesController extends Controller {
	protected $titulo = 'Perfiles';

	public $js = ['perfiles'];
	public $css = ['perfiles'];

	public $librerias = [
		'datatables',
		'jstree'
	];

	public function index() {
		return $this->view('admin::Perfiles');
	}

	public function buscar(Request $request, $id) {
		if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')){
			$perfil = Perfil::withTrashed()->find($id);
		}else{
			$perfil = Perfil::find($id);
		}

		if ($perfil) {
			$permisos = [];

			foreach ($perfil->permisos->toArray() as $_permiso) {
				$permisos[] = $_permiso['ruta'];
			}

			return array_merge($perfil->toArray(), [
				'permisos' => $permisos,
				's' => 's',
				'msj' => trans('controller.buscar'),
			]);
		}

		return trans('controller.nobuscar');
	}

	public function guardar(PerfilesRequest $request, $id = 0) {
		DB::beginTransaction();

		try {
			if ($id === 0){
				$perfil = Perfil::create($request->all());
				$id = $perfil->id;
			}else{
				$perfil = Perfil::find($id);
				$perfil->fill($request->all());
				$perfil->save();
			}
			
			$this->procesar_permisos($request, $id);
		} catch (Exception $e) {
			DB::rollback();
			return $e->errorInfo[2];
		}

		DB::commit();
		return [
			'id' => $perfil->id, 
			'texto' => $perfil->nombre, 
			's' => 's', 
			'msj' => trans('controller.incluir')
		];
	}

	protected function procesar_permisos($request, $id) {
		$permisos = explode(',', $request->input('permisos'));

		$permiso_perfil = PerfilesPermisos::where('perfil_id', $id)->delete();

		foreach ($permisos as $permiso) {
			$permiso = trim($permiso);

			PerfilesPermisos::create([
				'perfil_id' => $id,
				'ruta' => trim($permiso),
			]);
		}
	}

	public function eliminar(Request $request, $id) {
		try {
			Perfil::destroy($id);
		} catch (Exception $e) {
			return $e->errorInfo[2];
		}

		return ['s' => 's', 'msj' => trans('controller.eliminar')];
	}

	public function restaurar(Request $request, $id = 0) {
		try {
			Perfil::withTrashed()->find($id)->restore();
		} catch (Exception $e) {
			return $e->errorInfo[2];
		}

		return ['s' => 's', 'msj' => trans('controller.restaurar')];
	}

	public function destruir(Request $request, $id = 0) {
		try {
			Perfil::withTrashed()->find($id)->forceDelete();
		} catch (Exception $e) {
			return $e->errorInfo[2];
		}

		return ['s' => 's', 'msj' => trans('controller.destruir')];
	}

	public function arbol() {
		return Menu::estructura(true);
	}

	public function datatable(Request $request) {
		$sql = Perfil::select('id', 'nombre', 'deleted_at');

		if ($request->verSoloEliminados == 'true'){
			$sql->onlyTrashed();
		}elseif ($request->verEliminados == 'true'){
			$sql->withTrashed();
		}

		return Datatables::of($sql)
			->setRowId('id')
			->setRowClass(function ($registro) {
				return is_null($registro->deleted_at) ? '' : 'bg-red-thunderbird bg-font-red-thunderbird';
			})
			->make(true);
	}
}