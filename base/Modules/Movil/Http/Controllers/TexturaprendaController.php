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
use Modules\Movil\Http\Requests\TexturaprendaRequest;

//Modelos
use Modules\Movil\Model\Texturaprenda;

class TexturaprendaController extends Controller
{
    protected $titulo = 'Texturas de prendas';

    public $js = [
        'Texturaprenda'
    ];
    
    public $css = [
        'Texturaprenda'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::Texturaprenda', [
            'Texturaprenda' => new Texturaprenda()
        ]);
    }

    public function nuevo()
    {
        $Texturaprenda = new Texturaprenda();
        return $this->view('movil::Texturaprenda', [
            'layouts' => 'admin::layouts.popup',
            'Texturaprenda' => $Texturaprenda
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $Texturaprenda = Texturaprenda::find($id);
        return $this->view('movil::Texturaprenda', [
            'layouts' => 'admin::layouts.popup',
            'Texturaprenda' => $Texturaprenda
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $Texturaprenda = Texturaprenda::withTrashed()->find($id);
        } else {
            $Texturaprenda = Texturaprenda::find($id);
        }

        if ($Texturaprenda) {
            return array_merge($Texturaprenda->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(TexturaprendaRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $Texturaprenda = $id == 0 ? new Texturaprenda() : Texturaprenda::find($id);

            $Texturaprenda->fill($request->all());
            $Texturaprenda->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $Texturaprenda->id,
            'texto' => $Texturaprenda->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            Texturaprenda::destroy($id);
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
            Texturaprenda::withTrashed()->find($id)->restore();
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
            Texturaprenda::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = Texturaprenda::select([
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