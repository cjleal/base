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
use Modules\Movil\Http\Requests\ConfigCombinacionesRequest;

//Modelos
use Modules\Movil\Model\ConfigCombinaciones;
use Modules\Movil\Model\TipoPrenda;
use Modules\Movil\Model\TonoPiel;
use Modules\Movil\Model\Estaciones;
use Modules\Movil\Model\TipoPrendaDetalle;
use Modules\Movil\Model\Ocasiones;
class ConfigCombinacionesController extends Controller
{
    protected $titulo = 'Configuración de Combinaciones';

    public $js = [
        'ConfigCombinaciones',
        'bootstrap-colorpicker/js/bootstrap-colorpicker'
    ];
    
    public $css = [
        'ConfigCombinaciones',
        'bootstrap-colorpicker/css/colorpicker',
        'bootstrap-select/css/bootstrap-multiselect'
    ];

    public $librerias = [
        'datatables',
        'bootstrap-select', 
        'bootstrap-select/js/bootstrap-multiselect'
    ];

    public function index()
    {
        $categoriasPrendas = DB::table('tipo_prenda')
                                ->select('tipo_prenda.id', 'tipo_prenda.descripcion')
                                ->join('tipos_prendas_relacion','tipos_prendas_relacion.tipo_prenda_id','=','tipo_prenda.id')
                                //->where('tipos_prendas_relacion.tipo_prenda_id', '=' ,'tipo_prenda.id')
                                ->get();
        ;   
        return $this->view('movil::ConfigCombinaciones', [
            'ConfigCombinaciones' => new ConfigCombinaciones(),
            'tiposPrendas' => TipoPrenda::all(),
            'categoriasPrendas' => $categoriasPrendas,
            'tonoPiel' => TonoPiel::all(),
            'Estaciones' => Estaciones::all(),
            'TipoPrendasDetalle' => TipoPrendaDetalle::all(),
            'TipoOcasiones' => Ocasiones::all()
        ]);
    }
    public function Estacion(){
        return Estaciones::pluck('descripcion', 'id');
    }
    public function Sexo(){
        return ['f' => 'Femenino', 'm' => 'Masculino'];
    }
    public function TonoPieles(){
        return TonoPiel::pluck('descripcion', 'id');
    }
    public function TipoPrendasDetalle(){
        return TipoPrendaDetalle::pluck('descripcion', 'id');
    }
    public function TipoOcasiones(){
        return Ocasiones::pluck('descripcion', 'id');
    }
    public function RelacionDetalleRopa(Request $request, $id = 0){
        $sql = DB::table('tipo_prenda_detalle')
                    ->select('tipo_prenda_detalle.id', 'tipo_prenda_detalle.descripcion')
                    ->join('tipo_prenda', 'tipo_prenda.id', '=', 'tipo_prenda_detalle.tipo_prenda_id')
                    ->where('tipo_prenda_detalle.tipo_prenda_id', $id)
                    ->orderBy('tipo_prenda.id')
                    ->pluck('tipo_prenda_detalle.descripcion', 'tipo_prenda_detalle.id');
        
        $salida = ['s' => 'n' , 'msj' => 'La Categoria prenda no tiene prendas asociadas', 'tipoprendadetalle_id' => [] ];
        
        if (count($sql) > 0) {
            $salida = [
                        's' => 's' , 
                        'msj' => 'Prendas asociadas', 
                        'tipoprendadetalle_id' => $sql
                    ];
        }
        
        return $salida;
    }
    public function nuevo()
    {
        $ConfigCombinaciones = new ConfigCombinaciones();
        return $this->view('movil::ConfigCombinaciones', [
            'layouts' => 'admin::layouts.popup',
            'ConfigCombinaciones' => $ConfigCombinaciones
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $ConfigCombinaciones = ConfigCombinaciones::find($id);
        return $this->view('movil::ConfigCombinaciones', [
            'layouts' => 'admin::layouts.popup',
            'ConfigCombinaciones' => $ConfigCombinaciones
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $ConfigCombinaciones = ConfigCombinaciones::withTrashed()->find($id);
        } else {
            $ConfigCombinaciones = ConfigCombinaciones::find($id);
        }

        if ($ConfigCombinaciones) {
            $tipo_prenda_princ_detalle = $this->buscarTiposPrendas($id);
            $ocasiones_id = $this->buscarOcasiones($id);
            $sexo = $this->buscarSexo($id);
            $estaciones = $this->buscarEstaciones($id);
            $tono_piel = $this->buscarTonoPiel($id);
            $colores_prendas_princ = $this->buscarColoresPrendaPrincipal($id);
            $colores_prendas_sec = $this->buscarPrendasSecundariasAsociadas($id, $ConfigCombinaciones->prenda_princ_id);
            return array_merge($ConfigCombinaciones->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar'),
                'tipoprendadetalle_id' => $tipo_prenda_princ_detalle,
                'sexo' => $sexo,
                'estacion_id' => $estaciones,
                'tonopiel_id' => $tono_piel,
                'ocasiones_id' => $ocasiones_id,
                'colores_prendas_princ' => $colores_prendas_princ,
                'colores_prendas_sec'   => $colores_prendas_sec['tbody'],
                'check'                 => $colores_prendas_sec['check']
            ]);
        }
        return trans('controller.nobuscar');
    }
    public function buscarSexo($id){
        $sexo = DB::table('config_sexo')
                    ->select(['sexo'])
                    ->where('config_combina_id', $id)
                    ->pluck('sexo');
        return $sexo;
    }
    public function buscarEstaciones($id){
        $estaciones = DB::table('config_estacion')
                    ->select(['estaciones_id'])
                    ->where('config_combina_id', $id)
                    ->pluck('estaciones_id');
        return $estaciones;
    }
    public function buscarTonoPiel($id){
        $tonopiel = DB::table('config_tono_piel')
                    ->select(['tono_piel_id'])
                    ->where('config_combina_id', $id)
                    ->pluck('tono_piel_id');
        return $tonopiel;
    }
    public function buscarTiposPrendas($id){
        $tipo_prenda_princ_detalle = DB::table('config_tipos_prendas_princ_detalle')
                    ->select(['tipo_prenda_detalle_id'])
                    ->where('config_combina_id', $id)
                    ->pluck('tipo_prenda_detalle_id');
        return $tipo_prenda_princ_detalle;
    }
    public function buscarOcasiones($id){
        $ocasiones_id = DB::table('config_ocasiones')
                    ->select(['ocasiones_id'])
                    ->where('config_combina_id', $id)
                    ->pluck('ocasiones_id');
        return $ocasiones_id;
    }
    public function buscarColoresPrendaPrincipal($id){
        $colores_prendas_princ = DB::table('config_colores_prendas_princ')
                    ->select(['hexadecimal', 'r', 'g', 'b'])
                    ->where('config_combina_id', $id)
                    ->get();
        return $colores_prendas_princ;
    }
    public function guardar(ConfigCombinacionesRequest $request, $id = 0)
    {   
       //dd($request);
        $ok = $this->validar($request);
        if(!$ok) 
            return ['s' => 'n', 'msj' => 'No debe dejar campos Vacios!!!'];
        
        if (count($request->color) == 0)
            return ['s' => 'n', 'msj' => 'Debe ingresar el color de la prenda principal!!!'];

        if (count($request->descripcionCombina) == 0)
            return ['s' => 'n', 'msj' => 'Debe ingresar las combinaciones!!!'];

        DB::beginTransaction();
        try{
            $ConfigCombinaciones = $id == 0 ? new ConfigCombinaciones() : ConfigCombinaciones::find($id);

            $ConfigCombinaciones->fill($request->all());
            $ConfigCombinaciones->save();
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        $ok = $this->guardarEstaciones($ConfigCombinaciones->id, $request->estacion_id);
        if ($ok){
            $ok = $this->guardarSexo($ConfigCombinaciones->id, $request->sexo);
        }
        if ($ok){
            $ok = $this->guardarOcasiones($ConfigCombinaciones->id, $request->ocasiones_id);
        }
        if ($ok){
            $ok = $this->guardarTonoPiel($ConfigCombinaciones->id, $request->tonopiel_id);
        }
        if ($ok && count($request->color) > 0){

            $ok = $this->guardarColorPrendaPrincipal($ConfigCombinaciones->id, $request->color);
        }
        if ($ok && count($request->tipoprenda_id) > 0) {
             $ok = $this->guardarRelacion_tipos_prendas_sec($ConfigCombinaciones->id, $request->tipoprenda_id);
        }
        if ($ok && count($request->tipoprendadetalle_id) > 0) {
             $ok = $this->guardarRelacion_tipos_prendas_princ($ConfigCombinaciones->id, $request->tipoprendadetalle_id, $request->prenda_princ_id);
        }
        if ($ok && count($request->descripcionCombina) > 0){
            $ok = $this->guardarColoresCombinaciones($ConfigCombinaciones->id, $request->descripcionCombina);
        }
        return [
            'id'    => $ConfigCombinaciones->id,
            'texto' => $ConfigCombinaciones->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }
    public function validar($value='')
    {
        $retorna = true;
        if($value->descripcion == "")
            $retorna = false;
        if($value->prenda_princ_id == "")
            $retorna = false;
        if(count($value->sexo) == 0)
           $retorna = false;
        if(count($value->estacion_id) == 0)
            $retorna = false;
        if(count($value->tonopiel_id) == 0)
            $retorna = false;


        return $retorna;
    }
    public function guardarRelacion_tipos_prendas_princ($id, $tiposprendas, $prenda_princ_id){
        $delete = DB::table('config_tipos_prendas_princ_detalle')
                    ->where('config_combina_id', $id)
                    ->delete();
        DB::beginTransaction();
       
        foreach ($tiposprendas as $tiposprendas) {
          
            if($tiposprendas !== ""){
                try {
                    $insert = DB::table('config_tipos_prendas_princ_detalle')->insert(
                            [   
                                'config_combina_id'   => $id,
                                'prenda_princ_id' => $prenda_princ_id,
                                'tipo_prenda_detalle_id' => $tiposprendas
                            ]
                    );
                     
                } catch (QueryException $e) {
                    DB::rollback();
                    return $e->getMessage();
                }
            }
        }
        
       
        DB::commit();
        return true;            
    }
    public function guardarRelacion_tipos_prendas_sec($id, $tiposprendas){
        $delete = DB::table('config_tipos_prendas_sec_detalle')
                    ->where('config_combina_id', $id)
                    ->delete();
        DB::beginTransaction();
        foreach ($tiposprendas as $id_prenda_sec => $value) {
            foreach ($value as $prenda){
                if($prenda !== ""){
                    try {
                        $insert = DB::table('config_tipos_prendas_sec_detalle')->insert(
                                [   
                                    'config_combina_id'   => $id,
                                    'prenda_sec_id' => $id_prenda_sec,
                                    'tipo_prenda_detalle_id' => $prenda
                                ]
                        );
                         
                    } catch (QueryException $e) {
                        DB::rollback();
                        return $e->getMessage();
                    }
                }
            }
        }
       
        DB::commit();
        return true;            
    }
    public function guardarColoresCombinaciones($id, $colorHexa){
        $delete = DB::table('config_colores_prendas_sec')
                    ->where('config_combina_id', $id)
                    ->delete();
        DB::beginTransaction();
        foreach ($colorHexa as $id_prenda => $value) {
            foreach ($value as $color){
                if($color !== ""){
                    $rgb = array();
                    $rgb =  $this->rgb($color);
                    try {
                        $insert = DB::table('config_colores_prendas_sec')->insert(
                                [   
                                    'config_combina_id'   => $id,
                                    'tipo_prenda_id' => $id_prenda,
                                    'hexadecimal' => $color,
                                    'r' => $rgb['r'],
                                    'g' => $rgb['g'],
                                    'b' => $rgb['b']
                                ]
                        );
                         
                    } catch (QueryException $e) {
                        DB::rollback();
                        return $e->getMessage();
                    }
                }
            }
        }
       
        DB::commit();
        return true;
    }
    public function guardarColorPrendaPrincipal($config_combina_id, $color){
        $delete = DB::table('config_colores_prendas_princ')
                    ->where('config_combina_id', $config_combina_id)
                    ->delete();
        //dd($color);
            foreach($color as $color){
                $rgb = array();
                $rgb =  $this->rgb($color);
                DB::beginTransaction();
                    try{
                        $insert = DB::table('config_colores_prendas_princ')->insert(
                                    [   
                                        'config_combina_id' => $config_combina_id, 
                                        'hexadecimal' => $color,
                                        'r' => $rgb['r'],
                                        'g' => $rgb['g'],
                                        'b' => $rgb['b']
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
    public function guardarTonoPiel($config_combina_id = '', $tonopiel_id = '')
    {
        $delete = DB::table('config_tono_piel')
                    ->where('config_combina_id', $config_combina_id)
                    ->delete();
        foreach ($tonopiel_id as $tonopiel_id) {
            DB::beginTransaction();
                try{
                    $insert = DB::table('config_tono_piel')->insert(
                                [   
                                    'config_combina_id' => $config_combina_id, 
                                    'tono_piel_id' => $tonopiel_id
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
    public function guardarEstaciones($config_combina_id = '', $estacion_id = '')
    {
        $delete = DB::table('config_sexo')
                    ->where('config_combina_id', $config_combina_id)
                    ->delete();
        foreach ($estacion_id as $estacion) {
            DB::beginTransaction();
                try{
                    $insert = DB::table('config_estacion')->insert(
                                [   
                                    'config_combina_id' => $config_combina_id, 
                                    'estaciones_id' => $estacion
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
    public function guardarSexo($config_combina_id = '', $sexo = '')
    {
        $delete = DB::table('config_sexo')
                ->where('config_combina_id', $config_combina_id)
                ->delete();
        foreach ($sexo as $sexo) {
            DB::beginTransaction();
                try{
                    $insert = DB::table('config_sexo')->insert(
                                [   
                                    'config_combina_id' => $config_combina_id, 
                                    'sexo' => $sexo
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
    public function guardarOcasiones($id = '', $ocasiones = '')
    {
        $delete = DB::table('config_ocasiones')
                ->where('config_combina_id', $id)
                ->delete();
        foreach ($ocasiones as $ocasiones) {
            DB::beginTransaction();
                try{
                    $insert = DB::table('config_ocasiones')->insert(
                                [   
                                    'config_combina_id' => $id, 
                                    'ocasiones_id' => $ocasiones
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

    public function rgb($hex = ''){
        $hex = str_replace("#", "", $hex);
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        $rgb = array('r' => $r, 'g' => $g, 'b' => $b);
        return $rgb; 
    }
    public function eliminar(Request $request, $id = 0)
    {
        try{
            ConfigCombinaciones::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }
        $delete = DB::table('config_colores_prendas_princ')
                    ->where('config_combina_id', $id)
                    ->delete();
        $delete = DB::table('config_colores_prendas_sec')
                    ->where('config_combina_id', $id)
                    ->delete();
        $delete = DB::table('config_estacion')
                    ->where('config_combina_id', $id)
                    ->delete();
        $delete = DB::table('config_sexo')
                    ->where('config_combina_id', $id)
                    ->delete(); 
        $delete = DB::table('config_tono_piel')
                    ->where('config_combina_id', $id)
                    ->delete();
        $delete = DB::table('config_tipos_prendas_princ_detalle')
                    ->where('config_combina_id', $id)
                    ->delete();
        $delete = DB::table('config_tipos_prendas_sec_detalle')
                    ->where('config_combina_id', $id)
                    ->delete();              

        return ['s' => 's', 'msj' => trans('controller.eliminar')];
    }

    public function restaurar(Request $request, $id = 0)
    {
        try {
            ConfigCombinaciones::withTrashed()->find($id)->restore();
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
            ConfigCombinaciones::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }


    public function datatable(Request $request)
    {
        $sql = ConfigCombinaciones::select([
            'config_combinaciones.id', 
            'tipo_prenda.descripcion as nombre', 
            'config_combinaciones.descripcion', 
            'config_combinaciones.deleted_at'
        ])
        ->join('tipo_prenda','tipo_prenda.id','=','config_combinaciones.prenda_princ_id');
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

    function buscarPrendasAsociadas(Request $request){
        $CategoriasPrendas = DB::select('
                                            select b.tipo_prenda_id as id, b.tipo_prenda_id_relacion,
                                                   (select x.descripcion from tipo_prenda x where x.id= b.tipo_prenda_id_relacion )descripcion from 
                                                    tipo_prenda a, tipos_prendas_relacion b
                                            where a.id = b.tipo_prenda_id_relacion
                                                  and b.tipo_prenda_id = '. $request->prenda_principal .' order by a.id
                                        ');
        
        $tbody = ''; 
                          ;
        foreach ($CategoriasPrendas as $prenda) {
            $MultiSelect = DB::table('tipo_prenda_detalle')
                                ->select('tipo_prenda_detalle.id', 'tipo_prenda_detalle.descripcion')
                                ->join('tipo_prenda', 'tipo_prenda.id', '=', 'tipo_prenda_detalle.tipo_prenda_id')
                                ->where('tipo_prenda_detalle.tipo_prenda_id', $prenda->tipo_prenda_id_relacion)
                                ->get();
           //dd($MultiSelect);                         
            $tbody .= '<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption">
                                Combinaciones con '.$prenda->descripcion.'

                            </div>

                        </div>
                         <div class="portlet-body">
                            <div class="table-scrollable">
                                <table class="table table2 table-bordered table-hover" idPrenda="' . $prenda->tipo_prenda_id_relacion . '" style="min-height: 272px;">
                                    <thead>
                                        <tr>
                                        <th colspan="3"><label for="r"><i>Tipo Prenda</i> </label>
                                            </th>
                                            
                                        </tr>
                                        <tr>
                                            <th style="width: 50px" colspan="2  ">
                                                
                                                <select class="example-getting-started" multiple="multiple" name="tipoprenda_id[' . $prenda->tipo_prenda_id_relacion . '][]">
                                                ';
                                                foreach ($MultiSelect as $opciones) {
                                                        $tbody .='

                                                            <option value="'.$opciones->id.'">'.$opciones->descripcion.'</option>
                                                        ';
                                                    }
                                        $tbody .='  
                                                </select>

                                            </th>
                                            <th style="width: 55px;">
                                                <button class="btn blue tooltips agregar" data-container="body" data-placement="top" data-original-title="Agregar" prenda="' . $prenda->tipo_prenda_id_relacion . '">
                                                     <i class="fa fa-plus"></i>
                                                </button> 
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <input name="descripcionCombina['.$prenda->tipo_prenda_id_relacion.'][]" type="text" class="colorpicker-default form-control" value="">
                                            </td>  
                                            <td >
                                                <button class="btn red tooltips eliminar" data-container="body" data-placement="top" data-original-title="Eliminar">
                                                     <i class="fa fa-minus"></i>
                                                </button> 
                                            </td>
                                        </tr>
                                    </tbody>   
                                </table>
                    </div> </div></div></div>';  
        }
        //$tbody .= ''; 
        return [
                    's' => 's',
                    'msj' => 'Tipos de Prendas Asociadas',
                    'idtabla' => $request->prenda_principal,    
                    'tbody' => $tbody
                ];

    }
    function buscarPrendasSecundariasAsociadas($id, $prenda_princ_id){

        $contarEspecifica = DB::table('config_colores_prendas_sec')
                                ->where('config_combina_id', $id)
                                ->count();
        //dd($contarEspecifica);                        
        if($contarEspecifica > 0){
            $CategoriasPrendas = DB::select('
                                           select distinct a.tipo_prenda_id , c.descripcion 
                                            from config_colores_prendas_sec a, config_combinaciones b, tipo_prenda c
                                            where a.config_combina_id = b.id
                                                  and a.tipo_prenda_id = c.id
                                                  and a.config_combina_id = '. $id .'
                                                   order by 1
                                        ');
            
        }else{
            $CategoriasPrendas = DB::select('
                                            select b.tipo_prenda_id , b.tipo_prenda_id_relacion
                                                   (select x.descripcion from tipo_prenda x where x.id= b.tipo_prenda_id )descripcion from 
                                                    tipo_prenda a, tipos_prendas_relacion b
                                            where a.id = b.tipo_prenda_id
                                                  and b.tipo_prenda_id = '. $prenda_princ_id .' order by a.id
                                        ');
        }
        $seleccionarCheck = DB::table('tipo_prenda_detalle')
                    ->select(['tipo_prenda_detalle.id', 'config_tipos_prendas_sec_detalle.prenda_sec_id'])
                    ->join('config_tipos_prendas_sec_detalle', 'config_tipos_prendas_sec_detalle.tipo_prenda_detalle_id', '=',
                        'tipo_prenda_detalle.id')
                    ->where('config_combina_id', $id)
                    /*->pluck('tipo_prenda_detalle.id, config_tipos_prendas_sec_detalle.prenda_sec_id')*/
                    ->get();
        $check = [];
        foreach ($seleccionarCheck as  $valor) {
            $check[] =  $valor ;
            //$check['id'] =  $valor['id'] ;

        }
        $tbody = '';
        /*$seleccionarCheck = DB::select('
                select a.id from 
                    tipo_prenda_detalle a, config_tipos_prendas_sec_detalle b 
                    where a.id = b.tipo_prenda_detalle_id
                          and b.config_combina_id = '. $id .'
                          '
                ); 
        */
        
        
        foreach ($CategoriasPrendas as $prenda) {
            $MultiSelect = DB::table('tipo_prenda_detalle')
                                ->select('tipo_prenda_detalle.id', 'tipo_prenda_detalle.descripcion')
                                ->join('tipo_prenda', 'tipo_prenda.id', '=', 'tipo_prenda_detalle.tipo_prenda_id')
                                ->where('tipo_prenda_detalle.tipo_prenda_id', $prenda->tipo_prenda_id)
                                ->get();
            
            /**/
          $tbody .= '<div class="div form-group col-lg-4 col-md-4 col-sm-6 col-xs-12" style="min-height: 200px;">
                    <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption">
                                Combinaciones con '.$prenda->descripcion.'
                            </div>
                        </div>
                         <div class="portlet-body">
                            <div class="table-scrollable">
                                <table class="table table2 table-bordered table-hover" idPrenda="' . $prenda->tipo_prenda_id . '" style="min-height: 200px;">
                                    <thead>
                                        <tr>
                                            <th style="vertical-align: middle;  font-weight: bold;"><label for="r"><i>Tipo Prenda:</i> </label> </th>
                                            <th >
                                                <select class="example-getting-started" multiple="multiple" name="tipoprenda_id[' . $prenda->tipo_prenda_id . '][]">
                                                ';
                                                foreach ($MultiSelect as $opciones) {
                                                        $tbody .='

                                                            <option value="'.$opciones->id.'">'.$opciones->descripcion.'</option>
                                                        ';
                                                    }
                                        $tbody .='  
                                                </select>

                                            </th>
                                            <th style="width: 55px;">
                                                <button class="btn blue tooltips agregar" data-container="body" data-placement="top" data-original-title="Agregar" prenda="' . $prenda->tipo_prenda_id . '">
                                                     <i class="fa fa-plus"></i>
                                                </button> 
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
            '; 
            $countPrendas = DB::table('config_colores_prendas_sec')
                            ->where('config_combina_id', $id)
                            ->where('tipo_prenda_id', $prenda->tipo_prenda_id)
                            ->count();
            $coloresPrendas = DB::table('config_colores_prendas_sec')
                            ->where('config_combina_id', $id)
                            ->where('tipo_prenda_id', $prenda->tipo_prenda_id)
                            ->get();
            if ($countPrendas > 0){              
                    foreach ($coloresPrendas as $colores) {
                        
                    $tbody .='
                                                <tr>
                                                    <td colspan="2">
                                                        <input name="descripcionCombina['.$colores->tipo_prenda_id.'][]" type="text" class="colorpicker-default form-control" value="'.$colores->hexadecimal.'" style="background-color: rgb('.$colores->r.', '. $colores->g .' , '. $colores->b .')" readonly="readonly">
                                                    </td>  
                                                    <td >
                                                        <button class="btn red tooltips eliminar" data-container="body" data-placement="top" data-original-title="Eliminar">
                                                             <i class="fa fa-minus"></i>
                                                        </button> 
                                                    </td>
                                                </tr>
                    ';  
                    }
            }else{
                    $tbody .='
                                                <tr>
                                                    <td colspan="2">
                                                        <input name="descripcionCombina['.$prenda->tipo_prenda_id.'][]" type="text" class="colorpicker-default form-control" value="" readonly="readonly">
                                                    </td>  
                                                    <td >
                                                        <button class="btn red tooltips eliminar" data-container="body" data-placement="top" data-original-title="Eliminar">
                                                             <i class="fa fa-minus"></i>
                                                        </button> 
                                                    </td>
                                                </tr>
                    ';  
            }  
                    $tbody .= '
                                    </tbody>   
                                </table>
                    </div> </div></div></div>';  
           
        }
        //$tbody .= ''; 
        return ['tbody' => $tbody, 'check' => $check];
                

    }
}