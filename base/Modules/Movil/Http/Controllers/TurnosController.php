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
use Modules\Movil\Http\Requests\TurnosRequest;

//Modelos
use Modules\Movil\Model\Turnos;

class TurnosController extends Controller
{
    protected $titulo = 'Turnos';

    public $js = [
        'Turnos'
    ];
    
    public $css = [
        'Turnos'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::Turnos', [
            'Turnos' => new Turnos()
        ]);
    }

    public function nuevo()
    {
        $Turnos = new Turnos();
        return $this->view('movil::Turnos', [
            'layouts' => 'admin::layouts.popup',
            'Turnos' => $Turnos
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $Turnos = Turnos::find($id);
        return $this->view('movil::Turnos', [
            'layouts' => 'admin::layouts.popup',
            'Turnos' => $Turnos
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $Turnos = Turnos::withTrashed()->find($id);
        } else {
            $Turnos = Turnos::find($id);
        }

        if ($Turnos) {
            return array_merge($Turnos->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(TurnosRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $Turnos = $id == 0 ? new Turnos() : Turnos::find($id);

            $Turnos->fill($request->all());
            $Turnos->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $Turnos->id,
            'texto' => $Turnos->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            Turnos::destroy($id);
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
            Turnos::withTrashed()->find($id)->restore();
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
            Turnos::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = Turnos::select([
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