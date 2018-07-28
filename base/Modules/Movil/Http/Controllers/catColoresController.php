<?php

namespace Modules\Movil\Http\Controllers;

//Controlador Padre
use Modules\Movil\Http\Controllers\Controller;

//Dependencias
use DB;
use App\Http\Requests\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Database\QueryException;

//Request
use Modules\Movil\Http\Requests\CatColoresRequest;

//Modelos
use Modules\Movil\Model\CatColores;

class CatColoresController extends Controller
{
    protected $titulo = 'Categoria de Colores';

    public $js = [
        'CatColores'
    ];
    
    public $css = [
        'CatColores'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::CatColores', [
            'CatColores' => new CatColores()
        ]);
    }

    public function nuevo()
    {
        $CatColores = new CatColores();
        return $this->view('movil::CatColores', [
            'layouts' => 'admin::layouts.popup',
            'CatColores' => $CatColores
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $CatColores = CatColores::find($id);
        return $this->view('movil::CatColores', [
            'layouts' => 'admin::layouts.popup',
            'CatColores' => $CatColores
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $CatColores = CatColores::withTrashed()->find($id);
        } else {
            $CatColores = CatColores::find($id);
        }

        if ($CatColores) {
            return array_merge($CatColores->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(CatColoresRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $CatColores = $id == 0 ? new CatColores() : CatColores::find($id);

            $CatColores->fill($request->all());
            $CatColores->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $CatColores->id,
            'texto' => $CatColores->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            CatColores::destroy($id);
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.eliminar')];
    }

    public function restaurar(Request $request, $id = 0)
    {
        try {
            CatColores::withTrashed()->find($id)->restore();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.restaurar')];
    }

    public function destruir(Request $request, $id = 0)
    {
        try {
            CatColores::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = CatColores::select([
            'id', 'descripcion', 'deleted_at'
        ]);

        if ($request->verSoloEliminados == 'true') {
            $sql->onlyTrashed();
        } elseif ($request->verEliminados == 'true') {
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