<?php namespace Modules\Admin\Http\Controllers;

use Modules\Admin\Http\Controllers\Controller;
use DB;
use Validator;

//Request
use App\Http\Requests\Request;
use Modules\Admin\Http\Requests\PerfilRequest;

//Modelos
use Modules\Admin\Model\Usuario;

class PerfilController extends Controller {
	protected $titulo = 'Perfil';

	//public $autenticar = false;
	public $css = [
		'perfil_user'
	];
	public $js = [
		'perfilusuario'
	];

	public function index(){
		return $this->view('admin::Perfil', [
			'usuario' => auth()->user()
		]);
	}

	public function actualizar(PerfilRequest $request){
		DB::beginTransaction();
		try {
			Usuario::find(auth()->user()->id)->update($request->all());
		} catch (Exception $e) {
			DB::rollback();
			return $e->errorInfo[2];
		}

		DB::commit();

		return ['s' => 's', 'msj' => trans('controller.incluir')];
	}

	public function clave(Request $request){
		$usuario = auth()->user();
		
		if ($usuario->super !== 's'){
			$validator = Validator::make($request->all(), [
				'password' => ['required', 'confirmed', 'password', 'min:8', 'max:50'],
			]);

			if ($validator->fails()) {
				return response($validator->errors(), 422);
			}
		}

		try {
			$usuario = usuario::find($usuario->id);
			$usuario->password = $request->password;
			$usuario->save();
		} catch (Exception $e) {
			return $e->errorInfo[2];
		}

		return ['s' => 's', 'msj' => trans('controller.incluir')];
	}

	public function cambio(Request $request){
		$validator = Validator::make($request->all(), [
			'foto' => ['mimes:jpeg,png,jpg'],
		]);

		if ($validator->fails()) {
			return response($validator->errors(), 422);
		}
		
		$usuario = auth()->user();

		$file = $request->file('foto');
		$name = $usuario->usuario.'.'.$file->getClientOriginalExtension();
		$path = public_path('img/usuarios/');

		$file->move($path, $name);
		$filename = $path . $name;

		chmod($filename, 0777);

		Usuario::find($usuario->id)->update([
			'foto' => $name
		]);

		return ['s' => 's', 'msj' => trans('controller.incluir'), 'foto' => url('public/img/usuarios/' . $name)];
	}	 
} 