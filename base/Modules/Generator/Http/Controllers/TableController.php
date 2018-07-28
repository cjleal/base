<?php 
namespace Modules\Generator\Http\Controllers;

//Dependencias
use DB;
use Module;
use Config;
use Storage;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\Request;

use Validator;

//Controlador Padre
use Modules\Generator\Http\Controllers\Controller;

use Modules\Generator\Http\Requests\TablesRequest;

class TableController extends Controller {
	protected $titulo = 'Generator';

	public $js = ['table'];
	public $css = ['table'];

	public $librerias = ['jquery-ui', 'template', 'icheck'];

	public function index() {
		return $this->view('generator::table');
	}

	public function modulos(){
		$modulos = [];

		foreach (Module::all() as $key => $value) {
			$modulos[$key] = $value['name'];
		}

		return $modulos;
	}

	public function guardar(TablesRequest $request){
		$migration = $this->nombre_migracion($request);
		
		$contenido = [];

		if ($request->increments){
			$contenido[] = "\$table->increments('id')";
		}

		foreach ($request->name as $key => $name) {
			$length = $request->length[$key] > 0 ? ", " . $request->length[$key] : '';
			$campo = "\$table->" . $request->type[$key];

			switch ($request->type[$key]) {
				case 'integer':
					$campo .= "('" . $request->name[$key] . "')->unsigned()";
					break;
				
				default:
					$campo .= "('" . $request->name[$key] . "'" . $length . ")";
					break;
			}

			if ($request->null[$key]){
				$campo .= "->nullable()";
			}

			if ($request->unique[$key]){
				$campo .= "->unique()";
			}

			$contenido[] = $campo;
		}

		if ($request->timestamps){
			$contenido[] = "\$table->timestamps()";
		}

		if ($request->softDeletes){
			$contenido[] = "\$table->softDeletes()";
		}

		$cont = implode(";\n\t\t\t", $contenido) . ';';

		$this->archivo($request->modulo, 'migration', $migration, [
			'classMigration' 	=> studly_case($request->nombre_tabla),
			'table' 			=> $request->nombre_tabla,
			'cont' 				=> $cont
		]);

		return [
			's' => 's',
			'existe' => Schema::hasTable($request->nombre_tabla),
			'msj' => 'Tabla Creada'
		];
	}

	protected function nombre_migracion($request){
		$request->nombre_tabla = snake_case($request->nombre_tabla);

		$dir = $request->modulo . '/Database/Migrations';

		$archivos = Storage::disk('modules')->files($dir);

		$migration = date('Y_m_d_His') . '_' . $request->nombre_tabla;

		foreach ($archivos as $archivo) {
			$arch = substr($archivo, strrpos($archivo, '/') + 1);
			$arch = substr($arch, 0, strrpos($arch, '.'));
			$arch = substr($arch, 18);

			if ($arch == ''){
				continue;
			}

			if ($arch == $request->nombre_tabla){
				$arch = substr($archivo, strrpos($archivo, '/') + 1);
				$arch = substr($arch, 0, strrpos($arch, '.'));
				$migration = $arch;
			}
		}

		return $migration;
	}

	public function tablas(){
		$tablas = [];
		$tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

		foreach ($tables as $table) {
			$tablas[$table] = $table;
		}

		return $tablas;
	}
}