<?php

namespace Modules\Admin\Http\Controllers;

//Dependencias
use DB;
use URL;
use Yajra\Datatables\Datatables;

//Controlador Padre
use Modules\Admin\Http\Controllers\Controller;

//Request
use App\Http\Requests\Request;
use Modules\Admin\Http\Requests\UsuariosRequest;

//Modelos
use Modules\Admin\Model\Usuario;
use Modules\Admin\Model\Perfil;
use Modules\Admin\Model\Menu;
use Modules\Admin\Model\UsuarioPermisos;
use Modules\Admin\Model\Preguntas;
class UsuariosController extends Controller {
    protected $titulo = 'Usuarios';

    public $js = ['usuarios'];
    public $css = ['usuarios'];

    public $librerias = [
        'alphanum', 
        'maskedinput', 
        'datatables', 
        'jstree',
    ];

    public function index()
    {
        return $this->view('admin::Usuarios', [
            'Preguntas_pri' => Preguntas::all(),
            'Preguntas_seg' => Preguntas::all()

        ]);
    }

    public function buscar(Request $request, $id)
    {
        if ($this->permisologia($this->ruta() . '/restaurar') || $this->permisologia($this->ruta() . '/destruir')) {
            $usuario = Usuario::withTrashed()->find($id);
        } else {
            $usuario = Usuario::find($id);
        }

        if ($usuario) {
            $usuario->foto = URL::to("public/img/usuarios/" . $usuario->foto);
            $permisos = $usuario->UsuarioPermisos->pluck('ruta');

            return array_merge($usuario->toArray(), [
                'permisos' => $permisos,
                's' => 's',
                'msj' => trans('controller.buscar'),
            ]);
        }

        return trans('controller.nobuscar');
    }

    protected function data($request){
        $foto = "user.png";
        
        if ($file = $request->file('foto')) {
            $foto = $request->usuario . '.' . $file->getClientOriginalExtension();

            $path = public_path('img/usuarios/');
            $file->move($path, $foto);
            chmod($path . $foto, 0777);
        }

        $data = $request->all();
        $data['foto'] = $foto;

        if ($data['password'] == "") {
            unset($data['password']);
        }

        return $data;
    }
    

    public function guardar(UsuariosRequest $request, $id = 0)
    {
        //dd($request);
        DB::beginTransaction();
        try {
            $request->dni = rand(8,10000);
            $data = $this->data($request);
            
            $Usuario = $id == 0 ? new Usuario() : Usuario::find($id);

            $Usuario->fill($data);
            $Usuario->save();

            $this->procesar_permisos($request, $id);
        } catch (QueryException $e) {
            DB::rollback();
            return $e->getMessage();
        } catch (Exception $e) {
            DB::rollback();
            return $e->errorInfo[2];
        }

        DB::commit();

        return [
            'id' => $Usuario->id, 
            'texto' => $Usuario->nombre, 
            's' => 's', 
            'msj' => trans('controller.incluir')
        ];
    }

    protected function procesar_permisos($request, $id) {
        $permisos = explode(',', $request->input('permisos'));
        $permiso_perfil = UsuarioPermisos::where('usuario_id', $id)->delete();

        foreach ($permisos as $permiso) {
            $permiso = trim($permiso);
            if ($permiso == '') {
                continue;
            }

            UsuarioPermisos::create([
                'usuario_id' => $id,
                'ruta' => trim($permiso)
            ]);
        }
    }

    public function eliminar(Request $request, $id = 0) {
        try {
            $usuario = Usuario::destroy($id);
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.eliminar')];
    }

    public function restaurar(Request $request, $id = 0) {
        try {
            Usuario::withTrashed()->find($id)->restore();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.restaurar')];
    }

    public function destruir(Request $request, $id = 0) {
        try {
            Usuario::withTrashed()->find($id)->forceDelete();
        } catch (QueryException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            return $e->errorInfo[2];
        }

        return ['s' => 's', 'msj' => trans('controller.destruir')];
    }

    public function perfiles() {
        return perfil::pluck('nombre', 'id');
    }

    public function arbol() {
        return menu::estructura(true);
    }
   
    public function datatable(Request $request) {
        $sql = Usuario::select('id', 'dni', 'nombre', 'usuario', 'deleted_at');

        if ($request->verSoloEliminados == 'true'){
            $sql->onlyTrashed();
        }elseif ($request->verEliminados == 'true'){
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