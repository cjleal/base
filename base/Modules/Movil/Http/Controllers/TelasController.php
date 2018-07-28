<?php

namespace Modules\Movil\Http\Controllers;

//Controlador Padre
use Modules\Movil\Http\Controllers\Controller;

//Dependencias
use DB;
use App\Http\Requests\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Database\QueryException;
use Illuminate\Database\Query\Builder;

//Request
use Modules\Movil\Http\Requests\TelasRequest;
use Validator;
//Modelos
use Modules\Movil\Model\Telas;
use Modules\Movil\Model\Estaciones;
use Modules\Movil\Model\Imagenes;
class TelasController extends Controller
{
    protected $titulo = 'Telas';

    public $js = [
        'Telas'
    ];
    
    public $css = [
        'Telas'
    ];

    public $librerias = [
        'datatables',
        'jquery-ui',
        'jquery-ui-timepicker',
        'file-upload'
    ];

    public function index()
    {
        return $this->view('movil::Telas', [
            'Telas' => new Telas(), 
            'estaciones' => Estaciones::all()
        ]);
    }

    public function nuevo()
    {
        $Telas = new Telas();
        return $this->view('movil::Telas', [
            'layouts' => 'admin::layouts.popup',
            'Telas' => $Telas
        ]);
    }

    public function cambiar(Request $request, $id = 0)
    {
        $Telas = Telas::find($id);
        return $this->view('movil::Telas', [
            'layouts' => 'admin::layouts.popup',
            'Telas' => $Telas
        ]);
    }

    public function buscar(Request $request, $id = 0)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $rs = $Telas = Telas::withTrashed()->find($id);
        } else {
            $rs = $Telas = Telas::find($id);
        }

        $url = $this->ruta();
        $url = substr($url, 0, strlen($url) - 7);

        if ($rs) {
             $imgArray = [];
            
           
            $imgs = Imagenes::where('telas_id', $id)->get();

            foreach ($imgs as $img) {
                $id_archivo = str_replace('/', '-', $img->archivo);
                $name = substr($id_archivo, strrpos($id_archivo, '/') + 1);
                $imgArray[] = [
                    'id' => $img->id,
                    'name' => $img->nombre,
                    'url' => url('public/' . $img->url),
                    'thumbnailUrl' => url('public/' . $img->url) ,
                    'deleteType' => 'DELETE',
                    'deleteUrl' => url('http://localhost/cristian/Telas/eliminarimagen/' . $img->id),
                    'data' => [
                        'cordenadas' => [],
                        'leyenda' => [],
                        'descripcion' => []
                    ]
                ];
            }
            /**/
            $respuesta = array_merge($rs->toArray(), [
                's' => 's',
                'msj' => trans('controller.buscar'),
                'files' => $imgArray,
            ]);

            return $respuesta;
        }

        return trans('controller.nobuscar');
    }

    public function guardar(TelasRequest $request, $id = 0)
    {
       // dd($request->all());
        DB::beginTransaction();
        try{
            $data = $request;
            //dd($data);
            $Telas = $id == 0 ? new Telas() : Telas::find($id);
            $Telas->fill($request->all());
            $Telas->save();
            $this->guardar_imagenes($data, $id);
        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();

        return [
            'id'    => $Telas->id,
            'texto' => $Telas->nombre,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
        
       /* DB::beginTransaction();
        try{

            $data = $this->data($request);

            $archivos = json_decode($request->archivos);
            if ($id === 0){
                $Telas = Telas::create($data);
                $id = $Telas->id;
            }else{
                $Telas = Telas::find($id)->update($data);
            }

            $this->guardar_imagenes($archivos, $id);

        } catch(QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch(Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }
        DB::commit();
    */
        return [
            'id'    => $Telas->id,
            'texto' => $Telas->descripcion,
            's'     => 's',
            'msj'   => trans('controller.incluir')
        ];
    }

    public function eliminar(Request $request, $id = 0)
    {
        try{
            Telas::destroy($id);
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }
        $this->eliminarImagenesFisicasBd($id);
        return ['s' => 's', 'msj' => trans('controller.eliminar')];
    }
    public function eliminarImagenesFisicasBd($id){
        $imag = Imagenes::where('telas_id', $id)->where('telas_id', $id)->get();
        foreach ( $imag as  $imagen) {
            unlink('public/'.$imagenes->url);
        }
        $imag = Imagenes::where('telas_id', $id)->where('telas_id', $id)->forceDelete();
    }
    public function eliminarimagen(Request $request, $id = 0){
        try{

            $imagenes = Imagenes::where('telas_id', $id)->first(); 
            if(unlink('public/'.$imagenes->url)){
                $imag = DB::table('telas_img')->where('telas_id', $id)->forceDelete();
            }

            
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
            Telas::withTrashed()->find($id)->restore();
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
            Telas::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function datatable(Request $request)
    {
        $sql = Telas::select([
            'id', 'descripcion', 'estacion_id',  'deleted_at'
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
    public function subir(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'files.*' => ['required', 'mimes:jpeg,jpg,png'],
        ]);

        if ($validator->fails()) {
            return 'Error de Validacion';
        }

        $files = $request->file('files');
        $url = $this->ruta();
        $url = substr($url, 0, strlen($url) - 6);

        $rutaFecha = $this->getRuta($request->tela_id[0], $request->estacion_id[0]);
        $ruta = public_path('img/telas/' . $rutaFecha);

        $respuesta = array( 
            'files' => array(),
        );

        foreach ($files as $file) {
            do {
                $nombre_archivo = $this->random_string() . '.' . $file->getClientOriginalExtension();
            } while (is_file($ruta . $nombre_archivo));

            $id = str_replace('/', '-', $rutaFecha . $nombre_archivo);

            $respuesta['files'][] = [
                'id' => $id,
                'name' => $nombre_archivo,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'ruta' => url('imagen/small/' . $rutaFecha ),
                'url' => url('public/img/telas/' . $rutaFecha . $nombre_archivo),
                'thumbnailUrl' => url('public/img/telas/' . $rutaFecha . $nombre_archivo),
                'deleteType' => 'DELETE',
                'deleteUrl' => url($url . '/eliminarimagen/' . $id),
                'data' => [
                    'cordenadas' => [],
                    'leyenda' => '',
                    'descripcion' => ''
                ]
            ];

            $mover = $file->move($ruta, $nombre_archivo);
        }

        return $respuesta;
    }

    protected function getRuta($tela_id, $estacion_id) {
        return date('Y') . '/' . $tela_id . '/' . $estacion_id . '/';
    }
    protected function random_string($length = 20) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }


   protected function guardar_imagenes($archivos, $id = 0) {
        $imagenes = json_decode($archivos->archivos);
        $imag = Imagenes::where('telas_id', $id)->forceDelete();
        $i = 0;
        //dd($archivos);
        foreach ($imagenes as $archivo => $valor) {
            $img = array();
            $img = explode("-", $archivo);
            //$img = explode("-", $archivo[$i]);

            DB::beginTransaction();
            /*if($i == 1 ) {
                dd($img);
            }*/
            
                try {
                    DB::table('telas_img')->insert(
                        [
                            'telas_id' => $id,
                            'url' => 'img/telas/' . $img[0] . '/' . $img[1] . '/' . $img[2] . '/' . $img[3],
                            'nombre' => $img[3]
                        ]);
                 
                } catch (QueryException $e) {
                    DB::rollback();
                    return $e->getMessage();
                }
           
            DB::commit();
            $i++;
        }
       
    }
}