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
use Modules\Movil\Http\Requests\TipoPrendaRequest;

//Modelos
use Modules\Movil\Model\TipoPrenda;
use Modules\Movil\Model\Ocasiones;
class TipoPrendaController extends Controller
{
    protected $titulo = 'Categorias de Prenda';

    public $js = [
        'TipoPrenda'
    ];
    
    public $css = [
        'TipoPrenda'
    ];

    public $librerias = [
        'datatables',
        'bootstrap-select'
    ];

    public function index()
    {
       return $this->view('movil::TipoPrenda', [
            'TipoPrenda' => new TipoPrenda()
        ]);
    }
     public function Prenda(){
        return TipoPrenda::pluck('descripcion', 'id');
    }
    public function nuevo()
    {
        $TipoPrenda = new TipoPrenda();
        return $this->view('movil::TipoPrenda', [
            'layouts' => 'admin::layouts.popup',
            'TipoPrenda' => $TipoPrenda
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $TipoPrenda = TipoPrenda::find($id);
        return $this->view('movil::TipoPrenda', [
            'layouts' => 'admin::layouts.popup',
            'TipoPrenda' => $TipoPrenda
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $TipoPrenda = TipoPrenda::withTrashed()->find($id);
        } else {
            $TipoPrenda = TipoPrenda::find($id);
        }

        if ($TipoPrenda) {
            $prenda_relacion_id = $this->buscarPrendasRelacionadas($id);
            return array_merge($TipoPrenda->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar'),
                'prenda_relacion_id' => $prenda_relacion_id 
            ]);
        }
        
        return trans('controller.nobuscar');
    }
    public function buscarPrendasRelacionadas($id){
        $id_prenda = DB::table('tipos_prendas_relacion')
                    ->select(['tipo_prenda_id_relacion'])
                    ->where('tipo_prenda_id', $id)
                    ->pluck('tipo_prenda_id_relacion');
        return $id_prenda;
    }
    public function guardar(TipoPrendaRequest $request, $id = 0)
    {
        DB::beginTransaction();
        try{
            $TipoPrenda = $id == 0 ? new TipoPrenda() : TipoPrenda::find($id);

            $TipoPrenda->fill($request->all());
            $TipoPrenda->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();
        $ok = $this->guardarRelacionPrenda($TipoPrenda->id, $request->prenda_relacion_id);
        return [
            'id'    => $TipoPrenda->id,
            'texto' => $TipoPrenda->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }
    public function guardarRelacionPrenda($id, $relacion){
        $delete = DB::table('tipos_prendas_relacion')
                    ->where('tipo_prenda_id', $id)
                    ->delete();
        foreach ($relacion as $relacion) {
            DB::beginTransaction();
                try{
                    $insert = DB::table('tipos_prendas_relacion')->insert(
                                [   
                                    'tipo_prenda_id' => $id, 
                                    'tipo_prenda_id_relacion' => $relacion
                                ]
                        );
                } catch (QueryException $e) {
                    \DB::rollback();
                    return $e->getMessage();
                }
            DB::commit();
        }  
        return true;          
    }
    public function eliminar(Request $request, $id = 0)
    {
        try{
            TipoPrenda::withTrashed()->find($id)->forceDelete();
            $delete = DB::table('tipos_prendas_relacion')
                    ->where('tipo_prenda_id', $id)
                    ->delete();
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
            TipoPrenda::withTrashed()->find($id)->restore();
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
            TipoPrenda::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = TipoPrenda::select([
            'id', 'descripcion','deleted_at'
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