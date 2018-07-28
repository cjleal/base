<?php

namespace Modules\Admin\Http\Controllers;

//Dependencias

use DB;
use URL;
use Mail;
//Request
use Illuminate\Http\Request;
//use App\Http\Requests\Request;
//use Modules\Admin\Http\Requests\UsuariosRequest;

//Controlador Padre
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Http\Controllers\CorreoController;
use Modules\Admin\Model\Usuario;

class RecuperarController extends Controller {
	public $autenticar = false;

	protected $redirectTo = '/';   //antes era /escritorio
	protected $redirectPath = '/';   //antes era /escritorio
	protected $prefijo = '';

	public function __construct() {
		//$this->middleware('guest', ['except' => 'getSalir']);
		$this->prefijo = \Config::get('admin.prefix');

		$this->redirectTo = $this->prefijo . $this->redirectTo;
		$this->redirectPath = $this->prefijo . $this->redirectPath;
	}

	public function index() {
		 return $this->view('admin::Recuperar');
	}
	
	public function validarNombreUsuario(Request $request) {
	 	$usuario = Usuario::where('usuario', $request->usuario)->first();
       	
       	if(!$usuario){
			return ['s' => 'n', 'msj' => 'El Nombre de Usuario no Concuerda.'];
		}
		return [
			's' => 's', 
			'msj' => 'Usuario Valido!!!', 
			'primera' => $usuario->pregunta1->descripcion, 
			'segunda' => $usuario->pregunta2->descripcion
		];
    }
    public function respuestaUsuario(Request $request){
    	//dd($request->all());
	 	$usuario = Usuario::where('usuario', $request->usuario)->first();
	 	
       	$salida = ['s' => 's',  'msj' => 'Enviamos una clave temporal a su correo', 'ruta' => url('login'), 'id' => $usuario->id];

       	if(strtoupper($usuario->respuesta_pri) <> strtoupper($request->respuesta_pri)){
			$salida = ['s' => 'n', 'msj' => 'Los Datos no Concuerdan!!'];
			return $salida;
		}
		if(strtoupper($usuario->respuesta_seg) <> strtoupper($request->respuesta_seg)){
			$salida = ['s' => 'n', 'msj' => 'Los Datos no Concuerdan!!'];
			return $salida;
		}
		$claveTemporal = $this->GenerarClave();
		DB::beginTransaction();
	        try {
	             
	            DB::table('app_usuario')
		            ->where('id', $usuario->id)
		            ->update(['password' => bcrypt($claveTemporal)]);

	        } catch (QueryException $e) {
	            DB::rollback();
	            return $e->getMessage();
	        } catch (Exception $e) {
	            DB::rollback();
	            return $e->errorInfo[2];
	        }
		DB::commit();

		$this->EnviarEmail($usuario, $claveTemporal);
				
		return $salida;
		
    }
    public function EnviarEmail($usuario, $claveTemporal){
    	$title = "Recuperacion de Clave";
    	$message = "";
    	$destinatario = $usuario->correo;
    	$data = [
    				'title' => $title, 
    				'message' => $message, 
    				'claveTemporal' => $claveTemporal, 
    				'usuario' => $usuario->usuario, 
    				'destinatario' => $destinatario 
    			];
    	
    	$enviado = Mail::send('admin::MensajeRecuperacion', $data, function ($message) use ($destinatario){
					    $message->from('lealcristian46@gmail.com', 'Scotch.IO');
					    $message->to($destinatario);
					    $message->subject('Administrador Khaleesi: Contraseña Temporal ');
					});
    	
    	return $enviado;
    }
    function GenerarClave(){
    	$caracteres='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$longpalabra=8;
		for($pass='', $n=strlen($caracteres)-1; strlen($pass) < $longpalabra ; ) {
		  $x = rand(0,$n);
		  $pass.= $caracteres[$x];
		}
		return $pass;
    }
}