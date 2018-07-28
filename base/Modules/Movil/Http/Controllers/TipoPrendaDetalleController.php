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
use Modules\Movil\Http\Requests\TipoPrendaDetalleRequest;

//Modelos
use Modules\Movil\Model\TipoPrendaDetalle;
use Modules\Movil\Model\TipoPrenda;

class TipoPrendaDetalleController extends Controller
{
    protected $titulo = 'Tipo Prenda Detalle';

    public $js = [
        'TipoPrendaDetalle'
    ];
    
    public $css = [
        'TipoPrendaDetalle'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::TipoPrendaDetalle', [
            'TipoPrendaDetalle' => new TipoPrendaDetalle(),
            'categorias'    => TipoPrenda::all()
        ]);
    }

    public function nuevo()
    {
        $TipoPrendaDetalle = new TipoPrendaDetalle();
        return $this->view('movil::TipoPrendaDetalle', [
            'layouts' => 'admin::layouts.popup',
            'TipoPrendaDetalle' => $TipoPrendaDetalle
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $TipoPrendaDetalle = TipoPrendaDetalle::find($id);
        return $this->view('movil::TipoPrendaDetalle', [
            'layouts' => 'admin::layouts.popup',
            'TipoPrendaDetalle' => $TipoPrendaDetalle
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $TipoPrendaDetalle = TipoPrendaDetalle::withTrashed()->find($id);
        } else {
            $TipoPrendaDetalle = TipoPrendaDetalle::find($id);
        }

        if ($TipoPrendaDetalle) {
            return array_merge($TipoPrendaDetalle->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(TipoPrendaDetalleRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $TipoPrendaDetalle = $id == 0 ? new TipoPrendaDetalle() : TipoPrendaDetalle::find($id);

            $TipoPrendaDetalle->fill($request->all());
            $TipoPrendaDetalle->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $TipoPrendaDetalle->id,
            'texto' => $TipoPrendaDetalle->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            TipoPrendaDetalle::destroy($id);
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
            TipoPrendaDetalle::withTrashed()->find($id)->restore();
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
            TipoPrendaDetalle::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = TipoPrendaDetalle::select([
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