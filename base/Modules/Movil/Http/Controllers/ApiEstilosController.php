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
use Modules\Movil\Http\Requests\ApiEstilosRequest;

//Modelos
use Modules\Movil\Model\ApiEstilos;

class ApiEstilosController extends Controller
{
    protected $titulo = 'Tipos de Estilos';

    public $js = [
        'ApiEstilos'
    ];
    
    public $css = [
        'ApiEstilos'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::ApiEstilos', [
            'ApiEstilos' => new ApiEstilos()
        ]);
    }

    public function nuevo()
    {
        $ApiEstilos = new ApiEstilos();
        return $this->view('movil::ApiEstilos', [
            'layouts' => 'admin::layouts.popup',
            'ApiEstilos' => $ApiEstilos
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $ApiEstilos = ApiEstilos::find($id);
        return $this->view('movil::ApiEstilos', [
            'layouts' => 'admin::layouts.popup',
            'ApiEstilos' => $ApiEstilos
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {

        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $ApiEstilos = ApiEstilos::withTrashed()->find($id);
        } else {
            $ApiEstilos = ApiEstilos::find($id);
        }

        if ($ApiEstilos) {
            return array_merge($ApiEstilos->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(ApiEstilosRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $ApiEstilos = $id == 0 ? new ApiEstilos() : ApiEstilos::find($id);

            $ApiEstilos->fill($request->all());
            $ApiEstilos->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $ApiEstilos->id,
            'texto' => $ApiEstilos->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            ApiEstilos::destroy($id);
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
            ApiEstilos::withTrashed()->find($id)->restore();
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
            ApiEstilos::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = ApiEstilos::select([
            'id', 'nombre', 'descripcion', 'deleted_at'
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