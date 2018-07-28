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
use Modules\Movil\Http\Requests\ApiPreguntasEstilosRequest;

//Modelos
use Modules\Movil\Model\ApiPreguntasEstilos;

class ApiPreguntasEstilosController extends Controller
{
    protected $titulo = 'Preguntas según el Estilo';

    public $js = [
        'ApiPreguntasEstilos'
    ];
    
    public $css = [
        'ApiPreguntasEstilos'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        $estilos = DB::table('api_estilos')->get();
        return $this->view('movil::ApiPreguntasEstilos', [
            'ApiPreguntasEstilos' => new ApiPreguntasEstilos(),
            'estilos' => $estilos
        ]);
    }

    public function nuevo()
    {
        $ApiPreguntasEstilos = new ApiPreguntasEstilos();
        return $this->view('movil::ApiPreguntasEstilos', [
            'layouts' => 'admin::layouts.popup',
            'ApiPreguntasEstilos' => $ApiPreguntasEstilos
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $ApiPreguntasEstilos = ApiPreguntasEstilos::find($id);
        return $this->view('movil::ApiPreguntasEstilos', [
            'layouts' => 'admin::layouts.popup',
            'ApiPreguntasEstilos' => $ApiPreguntasEstilos
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $ApiPreguntasEstilos = ApiPreguntasEstilos::withTrashed()->find($id);
        } else {
            $ApiPreguntasEstilos = ApiPreguntasEstilos::find($id);
        }

        if ($ApiPreguntasEstilos) {
            return array_merge($ApiPreguntasEstilos->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(ApiPreguntasEstilosRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $ApiPreguntasEstilos = $id == 0 ? new ApiPreguntasEstilos() : ApiPreguntasEstilos::find($id);

            $ApiPreguntasEstilos->fill($request->all());
            $ApiPreguntasEstilos->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $ApiPreguntasEstilos->id,
            'texto' => $ApiPreguntasEstilos->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            ApiPreguntasEstilos::destroy($id);
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
            ApiPreguntasEstilos::withTrashed()->find($id)->restore();
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
            ApiPreguntasEstilos::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = ApiPreguntasEstilos::select([
            'api_preguntas_estilos.id', 
            'api_preguntas_estilos.descripcion', 
            'api_estilos.nombre', 
            'api_preguntas_estilos.deleted_at'
        ])
        ->join('api_estilos','api_estilos.id','=','api_preguntas_estilos.estilo_id');

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