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
use Modules\Movil\Http\Requests\EstacionesRequest;

//Modelos
use Modules\Movil\Model\Estaciones;

class EstacionesController extends Controller
{
    protected $titulo = 'Estaciones';

    public $js = [
        'Estaciones'
    ];
    
    public $css = [
        'Estaciones'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::Estaciones', [
            'Estaciones' => new Estaciones()
        ]);
    }

    public function nuevo()
    {
        $Estaciones = new Estaciones();
        return $this->view('movil::Estaciones', [
            'layouts' => 'admin::layouts.popup',
            'Estaciones' => $Estaciones
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $Estaciones = Estaciones::find($id);
        return $this->view('movil::Estaciones', [
            'layouts' => 'admin::layouts.popup',
            'Estaciones' => $Estaciones
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $Estaciones = Estaciones::withTrashed()->find($id);
        } else {
            $Estaciones = Estaciones::find($id);
        }

        if ($Estaciones) {
            return array_merge($Estaciones->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(EstacionesRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $Estaciones = $id == 0 ? new Estaciones() : Estaciones::find($id);

            $Estaciones->fill($request->all());
            $Estaciones->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $Estaciones->id,
            'texto' => $Estaciones->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            Estaciones::destroy($id);
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
            Estaciones::withTrashed()->find($id)->restore();
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
            Estaciones::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = Estaciones::select([
            'id', 'descripcion', 'estatus', 'deleted_at'
        ]);

        if ($request->verSoloEliminados == 'true') {
            $sql->onlyTrashed();
        } elseif ($request->verEliminados == 'true') {
            $sql->withTrashed();
        }

        return Datatables::of($sql)
            ->setRowId('id')
            ->editColumn('estatus', '
                @if($estatus == 0)
                    <span class="label label-default">Inactivo</span>
                @else
                    <span class="label label-info">Activo</span>
                @endif

            ')
            ->setRowClass(function ($registro) {
                return is_null($registro->deleted_at) ? '' : 'bg-red-thunderbird bg-font-red-thunderbird';
            })
            ->make(true);
    }

/*
    $sql = ticket::select([
                'ticket.titulo','ticket.descripcion',
                'ticket.categoria_id','ticket.estado as estado',
                'ticket.id','categoria.nombre'
            ])
        ->join('categoria','categoria.id','=','ticket.categoria_id')
        ->where('ticket.creado_por', \Auth::user()->usuario);

        return Datatables::of($sql)->setRowId('id')->editColumn('estado', '
                @if($estado == "pendiente")
                    <span class="label label-default">Pendiente</span>
                @elseif($estado == "asignado")
                    <span class="label label-info">Asignado</span>
                @elseif($estado == "sol_c")
                    <span class="label label-success">Solucionado Cerrado</span>
                @elseif($estado == "sol_nc")
                    <span class="label label-danger">No Cerrado</span>
                @endif

            ')->make(true);


    */


}