<?php

namespace Modules\Admin\Http\Controllers;

//Controlador Padre
use Modules\Admin\Http\Controllers\Controller;

//Dependencias
use DB;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

//Request
use Modules\Admin\Http\Requests\AppRequest;

//Modelos
use Modules\Admin\Model\app;

class AppController extends Controller {
	protected $titulo = 'App';

	public $librerias = [
		'bootstrap-fileinput'
	];

	public function index() {
		return $this->view('admin::app');
	}

	public function buscar(Request $request, $id = 0){
		$app = app::find($id);
		
		if ($app){
			return array_merge($app->toArray(), [
				's' => 's', 
				'msj' => trans('controller.buscar')
			]);
		}
		
		return trans('controller.nobuscar');
	}

	public function crear(appRequest $request){
		DB::beginTransaction();
		try{
			$app = app::create($request->all());
		}catch(Exception $e){
			DB::rollback();
			return $e->errorInfo[2];
		}
		DB::commit();

		return ['s' => 's', 'msj' => trans('controller.incluir')];
	}

	public function actualizar(appRequest $request, $id = 0){
		DB::beginTransaction();
		try{
			$app = app::find($id)->update($request->all());
		}catch(Exception $e){
			DB::rollback();
			return $e->errorInfo[2];
		}
		DB::commit();

		return ['s' => 's', 'msj' => trans('controller.incluir')];
	}

	public function eliminar(Request $request, $id = 0){
		try{
			$app = app::destroy($id);
		}catch(Exception $e){
			return $e->errorInfo[2];
		}

		return ['s' => 's', 'msj' => trans('controller.eliminar')];
	}

	public function restaurar(Request $request, $id = 0) {
		try {
			$app = app::withTrashed()->find($id)->restore();
		} catch (Exception $e) {
			return $e->errorInfo[2];
		}

		return ['s' => 's', 'msj' => trans('controller.restaurar')];
	}

	public function destruir(Request $request, $id = 0) {
		try {
			$app = app::withTrashed()->find($id)->forceDelete();
		} catch (Exception $e) {
			return $e->errorInfo[2];
		}

		return ['s' => 's', 'msj' => trans('controller.destruir')];
	}

	public function datatable(){
		$sql = app::select([
			'" . implode("', '", $this->columnas) . "'
		]);

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