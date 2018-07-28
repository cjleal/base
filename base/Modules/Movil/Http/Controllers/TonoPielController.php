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
use Modules\Movil\Http\Requests\TonoPielRequest;

//Modelos
use Modules\Movil\Model\TonoPiel;

class TonoPielController extends Controller
{
    protected $titulo = 'Tono Piel';

    public $js = [
        'TonoPiel'
    ];
    
    public $css = [
        'TonoPiel'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::TonoPiel', [
            'TonoPiel' => new TonoPiel()
        ]);
    }

    public function nuevo()
    {
        $TonoPiel = new TonoPiel();
        return $this->view('movil::TonoPiel', [
            'layouts' => 'admin::layouts.popup',
            'TonoPiel' => $TonoPiel
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $TonoPiel = TonoPiel::find($id);
        return $this->view('movil::TonoPiel', [
            'layouts' => 'admin::layouts.popup',
            'TonoPiel' => $TonoPiel
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $TonoPiel = TonoPiel::withTrashed()->find($id);
        } else {
            $TonoPiel = TonoPiel::find($id);
        }

        if ($TonoPiel) {
            return array_merge($TonoPiel->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(TonoPielRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $TonoPiel = $id == 0 ? new TonoPiel() : TonoPiel::find($id);

            $TonoPiel->fill($request->all());
            $TonoPiel->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $TonoPiel->id,
            'texto' => $TonoPiel->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            TonoPiel::destroy($id);
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
            TonoPiel::withTrashed()->find($id)->restore();
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
            TonoPiel::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = TonoPiel::select([
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