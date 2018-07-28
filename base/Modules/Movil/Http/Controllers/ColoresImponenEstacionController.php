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
use Modules\Movil\Http\Requests\ColoresImponenEstacionRequest;

//Modelos
use Modules\Movil\Model\ColoresImponenEstacion;
use Modules\Movil\Model\Estaciones;

class ColoresImponenEstacionController extends Controller
{
    protected $titulo = 'Colores que se imponen según la Estación';

    public $js = [
        'ColoresImponenEstacion',
        'bootstrap-colorpicker/js/bootstrap-colorpicker'
    ];
    
    public $css = [
        'ColoresImponenEstacion',
        'bootstrap-colorpicker/css/colorpicker'
    ];

    public $librerias = [
        'datatables'
    ];

    public function index()
    {
    /*
        'EstacionesGuardadas' => ColoresImponenEstacion::select('colores_imponen_estacion.descripcion')->join('estaciones', 'estaciones.id', '=', 'colores_imponen_estacion.estaciones_id')->where('estaciones.estatus', 1)->get()
    
    */

        return $this->view('movil::ColoresImponenEstacion', [
            'ColoresImponenEstacion' => new ColoresImponenEstacion(),
            'estaciones' => Estaciones::all(),
            'EstacionesGuardadas' => ColoresImponenEstacion::all()/*select('colores_imponen_estacion.descripcion')->join('estaciones', 'estaciones.id', '=', 'colores_imponen_estacion.estaciones_id')*/
        ]);
    }

    public function nuevo()
    {
        $ColoresImponenEstacion = new ColoresImponenEstacion();
        return $this->view('movil::ColoresImponenEstacion', [
            'layouts' => 'admin::layouts.popup',
            'ColoresImponenEstacion' => $ColoresImponenEstacion
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $ColoresImponenEstacion = ColoresImponenEstacion::find($id);
        return $this->view('movil::ColoresImponenEstacion', [
            'layouts' => 'admin::layouts.popup',
            'ColoresImponenEstacion' => $ColoresImponenEstacion
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $ColoresImponenEstacion = ColoresImponenEstacion::withTrashed()->find($id);
        } else {
            $ColoresImponenEstacion = ColoresImponenEstacion::find($id);
        }

        if ($ColoresImponenEstacion) {
            return array_merge($ColoresImponenEstacion->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar')
            ]);
        }

        return trans('controller.nobuscar');
    }

    public function guardar(ColoresImponenEstacionRequest $request, $id = 0)
    {
        $id = 0;
        //dd($request->descripcion);
        DB::beginTransaction();
        foreach ($request->estacion_id as $id => $value) {
            $id = $value;
           
            if ($id == 0) {
                continue;
            }

            // si no se limpia toda la base de datos (truncate)
            ColoresImponenEstacion::where('estaciones_id', $id)->forceDelete();

            for ($i = 0; $i <= count($request->descripcion[$id]) - 1; $i++) {
                if($request->descripcion[$id][$i] !== ""){
                    try {
                        $datos = array(
                            'descripcion'   => $request->descripcion[$id][$i],
                            'estaciones_id' => $id
                        );
                        $ColoresPreDomina =  new ColoresImponenEstacion() ;
                        $ColoresPreDomina->fill($datos);
                        $ColoresPreDomina->save();
                         
                    } catch (QueryException $e) {
                        DB::rollback();
                        return $e->getMessage();
                    }
                }
            }
        }
       
        DB::commit();

        return [
            'estacion_id'    => $id,
            'texto' => 'Colores definidos',
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            ColoresImponenEstacion::destroy($id);
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
            ColoresImponenEstacion::withTrashed()->find($id)->restore();
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
            ColoresImponenEstacion::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = ColoresImponenEstacion::select([
            'id', 'descripcion', 'estaciones_id', 'deleted_at'
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