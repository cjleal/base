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
use Modules\Movil\Http\Requests\OcasionesRequest;

//Modelos
use Modules\Movil\Model\Ocasiones;

class OcasionesController extends Controller
{
    protected $titulo = 'Ocasiones';

    public $js = [
        'Ocasiones'
    ];
    
    public $css = [
        'Ocasiones'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
        return $this->view('movil::Ocasiones', [
            'Ocasiones' => new Ocasiones()
        ]);
    }

    public function nuevo()
    {
        $Ocasiones = new Ocasiones();
        return $this->view('movil::Ocasiones', [
            'layouts' => 'admin::layouts.popup',
            'Ocasiones' => $Ocasiones
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $Ocasiones = Ocasiones::find($id);
        return $this->view('movil::Ocasiones', [
            'layouts' => 'admin::layouts.popup',
            'Ocasiones' => $Ocasiones
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $Ocasiones = Ocasiones::withTrashed()->find($id);
            $Preguntas =  DB::table('api_preguntas_ocasiones')
                    ->select('descripcion')
                    ->where('ocasiones_id', $Ocasiones->id)
                    ->get();
        } else {
            $Ocasiones = Ocasiones::find($id);
            $Preguntas =  DB::table('api_preguntas_ocasiones')
                    ->select('descripcion')
                    ->where('ocasiones_id', $Ocasiones->id)
                    ->get();
        }

        if ($Ocasiones) {
            return array_merge($Ocasiones->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar'),
                'Preguntas' => $Preguntas
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(OcasionesRequest $request, $id = 0)
    {
        
        DB::beginTransaction();
        try{
            $Ocasiones = $id == 0 ? new Ocasiones() : Ocasiones::find($id);

            $Ocasiones->fill($request->all());
            $Ocasiones->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();
        if(count($request->descripcionP) > 0){
             DB::table('api_preguntas_ocasiones')
                    ->where('ocasiones_id', $Ocasiones->id)
                    ->delete();
            for ($i=0; $i < count($request->descripcionP) ; $i++) { 
                $data =[
                        'descripcion' => $request->descripcionP[$i],
                        'ocasiones_id' => $Ocasiones->id
                    ];
                 DB::table('api_preguntas_ocasiones')->insert($data);   
            }
            
        }

        return [
            'id'    => $Ocasiones->id,
            'texto' => $Ocasiones->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')

        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            Ocasiones::destroy($id);
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
            Ocasiones::withTrashed()->find($id)->restore();
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
            Ocasiones::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = Ocasiones::select([
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