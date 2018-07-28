<?php

namespace Modules\Admin\Http\Controllers;

//Dependencias

use DB;
use URL;
//Request
use Illuminate\Http\Request;
//use App\Http\Requests\Request;
//use Modules\Admin\Http\Requests\UsuariosRequest;

//Controlador Padre
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Model\Usuario;

class RestablecerClaveController extends Controller {
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
		 return $this->view('admin::RestablecerClave');
	}
}