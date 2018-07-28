<?php 
namespace Modules\Generator\Http\Controllers;

//Dependencias
use DB;
use Schema;
use Module;
use Storage;
use Config;
use Yajra\Datatables\Datatables;
use App\Http\Requests\Request;

use Illuminate\Filesystem\Filesystem;

use Validator;

//Controlador Padre
use Modules\Generator\Http\Controllers\Controller;
use Modules\Generator\Http\Requests\GeneratorRequest;


class GeneratorController extends Controller {
	protected $titulo = 'Generator';

	public $js = ['generator'];
	public $css = ['generator'];

	public $librerias = ['jquery-ui', 'template', 'icheck'];

	protected $indices = [];
	protected $columnas = [];
	protected $columnas_tipos = [];

	protected $modulo = '';
	protected $nombre = '';
	protected $tabla  = '';

	protected $opciones = [];
	protected $ruta     = '';

	protected $timestamps  = false;
	protected $softDeletes = false;

	public function index() {
		$this->modulo = 'Generator';
		
		return $this->view('generator::generator');
	}

	public function guardar(GeneratorRequest $request) {
		$this->modulo = studly_case($request->modulo);

		$this->tabla  = trim(strtolower($request->tabla));
		$this->nombre = $this->tabla;

		$this->campos 		= $request->campos;

		$this->relacion 	= $request->relacion ? $request->relacion : [];
		$this->model 		= $request->model;
		$this->foreign_key 	= $request->foreign_key;
		$this->local_key 	= $request->local_key;

		if ($request->estructura){
			$this->campos = [];
		}

		$this->timestamps = !!$request->timestamps;
		$this->softDeletes = !!$request->softDeletes;


		if ($request->controller){
			$this->controllers();
		}

		if ($request->request){
			$this->requests();
		}

		if ($request->model){
			$this->model();
		}

		if ($request->view){
			$this->_view();
		}

		if ($request->css){
			$this->css();
		}

		if ($request->js){
			$this->js();
		}

		if ($request->route){
			$this->route();
		}

		return ['s' => 's', 'msj' => 'Archivos Generados Satisfactoriamente'];
	}

	protected function controllers(){
		$namespace = 'Modules\\' . studly_case($this->modulo);
		$tabla = strtolower($this->tabla);

		$campos = ['id'];
		foreach ($this->campos as $campo) {
			$campos[] = $campo['id'];
		}

		$this->archivo($this->modulo, 'controller', '', [
			'namespace' 		=> $namespace . '\\Http\\Controllers',
			'namespaceParent' 	=> $namespace . '\\Http\\Controllers\\Controller',
			'request' 			=> $namespace . '\\Http\\Requests\\' . studly_case($this->tabla) . 'Request',
			'model' 			=> $namespace . '\\Model\\' . studly_case($tabla),
			'classname' 		=> studly_case($this->tabla) . 'Controller',
			'titulo' 			=> $this->nombre(),
			'view' 				=> strtolower($this->modulo) . '::' . studly_case($this->tabla),
			'table' 			=> studly_case($this->tabla),
			'datatable' 		=> "'" . implode("', '", $campos) . "'" . ($this->softDeletes ? ", 'deleted_at'" : "")
		]);

		return true;
	}

	protected function requests(){
		$columnas = $this->campos;
		
		$reglas = [];

		foreach($columnas as $columna){
			if (!isset($columna['validate'])){
				continue;
			}
			
			$propiedades = $columna['validate'];

			$reglas[] = "'" . $columna['id'] . "' => ['" . implode("', '", $propiedades) . "']";
		}

		$this->archivo($this->modulo, 'request', '', [
			'namespace' => "Modules\\" . studly_case($this->modulo) . "\\Http\\Requests",
			'classname' => studly_case($this->tabla) . 'Request',
			'table' => $this->tabla,
			'rules' => "[\n\t\t" . implode(", \n\t\t", $reglas) . "\n\t]"
		]);

		return true;
	}

	protected function model(){
		$columnas = $this->campos;
		
		$data = [
			'namespace' => "Modules\\" . studly_case($this->modulo) . "\\Model" . rtrim($this->ruta, '\\'),
			'extends' => 'Model',
			'table' => $this->tabla,
			'namespaceParent' => 'Illuminate\Database\Eloquent\Model',
			'classname' => studly_case($this->tabla),
			'fillable' => [],
			'hidden' => [],
			'campos' => [],
			'options' => [],
			'includes' => [],
			'metodos' => ''
		];

		if ($this->timestamps){
			$data['hidden'] = ['created_at', 'updated_at'];
		}

		if ($this->softDeletes){
			$data['extends'] = 'modelo';
			$data['namespaceParent'] = 'Modules\Admin\Model\Modelo';
			$data['hidden'][] = 'deleted_at';
		}

		$campos = [];
		$modelos = $this->modelos();
		foreach ($this->campos as $campo) {
			$nombre_columna = $campo['id'];

			$data['fillable'][] = $nombre_columna;

			$propiedades = [
				'name' 			=> $campo['name'],
				'type' 			=> $campo['type'],
				'label'			=> $campo['label'],
				'placeholder'	=> $campo['placeholder'],
			];

			if ($propiedades['name'] == $nombre_columna){
				unset($propiedades['name']);
			}

			if (isset($campo['cont_class']) && $campo['cont_class'] != ''){
				$propiedades['cont_class'] = $campo['cont_class'];
			}

			if (isset($campo['url']) && $campo['url'] != ''){
				$propiedades['url'] = $campo['url'];
			}

			if (isset($campo['data']) && !empty($campo['data'])){
				$model = $campo['data'][0];
				foreach ($modelos as $modelo) {
					if (snake_case($modelo[1]) == $campo['data'][0]){
						$model = $modelo[0];
					}
				}

				$data['includes'][] = 'use ' . $model . ';';

				$data['options'][] = "\$this->campos['$nombre_columna']['options'] = " . studly_case($campo['data'][0]) . "::pluck('" . $campo['data'][2] . "', '" . $campo['data'][1] . "');";
			}

			$campos[$nombre_columna] = $propiedades;
		}

		foreach ($this->relacion as $id => $relacion) {
			$metodo = substr($this->model[$id], strrpos($this->model[$id], '\\') + 1);
			$metodo = snake_case($metodo);
			//dd($metodo);
			$data['metodos'] .= "public function " . $metodo . "()
	{
		return \$this->" . $relacion . "('" . $this->model[$id] . "'" . ($this->foreign_key[$id] != '' ? ", " . $this->foreign_key[$id] : '') . ($this->local_key[$id] != '' ? ", " . $this->local_key[$id] : '') . ");
	}

	"; 
		}


		$data['fillable'] = json_encode($data['fillable']);
		$data['hidden'] = json_encode($data['hidden']);
		$data['campos'] = str_replace(['{', '}', '": ', '"'], ['[', ']', '" => ', "'"], json_encode($campos, JSON_PRETTY_PRINT));
		$data['options'] = implode("\n\t\t", $data['options']);
		$data['includes'] = implode("\n", $data['includes']);
		
		$this->archivo($this->modulo, 'model', '', $data);
	}

	protected function _view(){
		$columnas = $this->campos;
		
		unset($columnas['id']);
		$thtable = [];
		foreach ($columnas as $columna){
			if ($columna['id'] == 'id'){
				continue;
			}

			$thtable[] = "'" . $this->nombre($columna['label']) . "' => '" . (100 / (count($columnas))) . "'";
		}

		$this->archivo($this->modulo, 'view', '', [
			'thtable' => implode(",\n\t\t", $thtable),
			'nombre' => $this->nombre(),
			'table' => studly_case($this->tabla)
		]);
		return;
	}

	protected function css(){
		$this->archivo($this->modulo, 'css', '', [
			'table' => studly_case($this->tabla)
		]);

		return;
	}

	protected function js(){
		$columnas = $this->campos;
		
		unset($columnas['id']);
		$camposDT = [];

		foreach ($columnas as $columna){
			$camposDT[] = [
				'data' => $columna['id'],
				'name' => $columna['id']
			];
		}

		$this->archivo($this->modulo, 'js', '', [
			'campos' => json_encode($camposDT)
		]);

		return;
	}

	protected function route(){
		$gestor = Storage::disk('modules');
		$archivo = $this->modulo . '/Routes/web.php';

		$_contenido = $gestor->get($archivo);
		$_contenido = explode("\n", $_contenido);

		$contenido = '';
		$insercion = false;
		
		$class = studly_case($this->tabla) . 'Controller';

		foreach ($_contenido as $linea) {
			if (strpos($linea, $class) !== false && !$insercion){
				return true;
			}
		}

		foreach ($_contenido as $linea) {
			if (strpos($linea, '//{{route}}') !== false && !$insercion){
				$insercion = true;
				$linea = "
	Route::group(['prefix' => '$this->tabla'], function() {
		Route::get('/', 				'$class@index');
		Route::get('nuevo', 			'$class@nuevo');
		Route::get('cambiar/{id}', 		'$class@cambiar');
		
		Route::get('buscar/{id}', 		'$class@buscar');

		Route::post('guardar',			'$class@guardar');
		Route::put('guardar/{id}', 		'$class@guardar');

		Route::delete('eliminar/{id}', 	'$class@eliminar');
		Route::post('restaurar/{id}', 	'$class@restaurar');
		Route::delete('destruir/{id}', 	'$class@destruir');

		Route::get('datatable', 		'$class@datatable');
	});\n\n" . $linea;
			}
			$contenido .= $linea . "\n";
		}

		$gestor->put($archivo, trim($contenido));

		return;
	}

	protected function nombre($nombre = ''){
		return ucwords(str_replace('_', ' ', $nombre === '' ? $this->tabla : $nombre));
	}

	public function campos(Request $request, $tabla){
		$this->tabla = $tabla;
		
		$campos = $this->listarCampos();
		$campos['campos'] = array_values($campos['campos']);

		return $campos;
	}

	public function modulos(){
		$modulos = [];

		foreach (Module::all() as $key => $value) {
			$modulos[$key] = $value['name'];
		}

		return $modulos;
	}

	public function modelos(){
		$gestor = Storage::disk('modules');

		$modelos = [];
		$path = Config::get('modules.path');
		$namespace = Config::get('modules.namespace');
		foreach (Module::all() as $key => $value) {
			$namespace_module = $namespace . $value['basename'] . '\\Model';
			$files = $gestor->allFiles($value['basename'] . '/Model');
			
			foreach ($files as $file) {
				$file = str_replace($value['basename'] . '/Model/', '', $file);
				$file = substr($file, 0, -4);

				$modelos[] = [
					$namespace_module . '\\' . $file,
					$file
				];
			}
		}

		return $modelos;
	}

	public function tablas(){
		$tablas = [];
		$tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

		foreach ($tables as $table) {
			$tablas[$table] = $table;
		}

		return $tablas;
	}

	public function listarCampos(){
		$conexion = DB::connection();
		$schema = $conexion->getDoctrineSchemaManager();
		$this->indices = $schema->listTableIndexes($this->tabla);
		//dd($this->indices);

		$this->columnas = Schema::getColumnListing($this->tabla);
		
		/*
		if ($this->opciones["estructura"]){
			return;
		}
		*/
		
		if (empty($this->columnas)){
			return;
		}

		foreach ($this->columnas as $nombre_columna) {
			if ($nombre_columna === 'created_at' || $nombre_columna === 'updated_at'){
				$this->timestamps = true;
				continue;
			}

			if ($nombre_columna === 'deleted_at'){
				$this->softDeletes = true;
				continue;
			}

			//$this->columnas_tipos[$nombre_columna] = DB::connection()->getDoctrineColumn($this->tabla, $nombre_columna)->getType()->getName();

			$this->columnas_tipos[$nombre_columna] = $conexion->getDoctrineColumn($this->tabla, $nombre_columna);
			$this->columnas_tipos[$nombre_columna]->isForeign = false;
			$this->columnas_tipos[$nombre_columna]->isUnique = false;
			$this->columnas_tipos[$nombre_columna]->isPrimary = false;
			foreach ($this->indices as $indice) {
				if (in_array($nombre_columna, $indice->getColumns())){
					if ($indice->isUnique()){
						$this->columnas_tipos[$nombre_columna]->isUnique = true;
					}elseif ($indice->isPrimary()){
						$this->columnas_tipos[$nombre_columna]->isPrimary = true;
					}

					if (strpos($indice->getName(), 'foreign') !== false) {
						$this->columnas_tipos[$nombre_columna]->isForeign = true;
					}
				}
			}
		}

		$campos = [];
		foreach ($this->columnas_tipos as $nombre_columna => $campo) {
			if ($campo->getAutoincrement() || $nombre_columna === 'created_at' || $nombre_columna === 'updated_at' || $nombre_columna === 'deleted_at'){
				continue;
			}
			
			$label = title_case(str_replace('_', ' ', snake_case($nombre_columna)));
			$label = str_replace(' Id', '', $label);

			$placeholder = $label . ' del ' . title_case(str_replace('_', ' ', snake_case($this->tabla)));
			

			$propiedades = [
				'id'			=> $nombre_columna,
				'name'			=> $nombre_columna,
				'type' 			=> $this->getInputType($campo->getType()->getName()),
				'label'			=> $label,
				'placeholder'	=> $placeholder,
				'cont_class'	=> 'col-lg-3 col-md-4 col-sm-6 col-xs-12'
			];

			$validate = [];

			if ($campo->getNotnull()){
				$propiedades['required'] = true;
				$validate[] = 'required';
			}
			
			switch ($campo->getType()->getName()) {
				case 'integer':
					$validate[] = 'integer';
					break;

				case 'string':
					$validate[] = 'min:3';
					$validate[] = 'max:' . $campo->getLength();
					break;

				case 'date':
					$validate[] = 'date_format:"d/m/Y"';
					break;
			}

			if ($campo->isUnique){
				$validate[] = 'unique:' . $this->tabla . ',' . $nombre_columna;
			}

			if ($campo->isForeign){
				$propiedades['type'] = 'select';
				//$propiedades['placeholder'] = '- Seleccione';
				$propiedades['placeholder'] = '- Seleccione un ' . $label;
				$url = 'Agrega una URL Aqui!';
				
				foreach ($this->indices as $indice) {
					if (in_array($nombre_columna, $indice->getColumns())){
						$nombreindice = str_replace(['_id_foreign', $this->tabla], '', $indice->getname());
						$nombreindice = trim($nombreindice, '_');
						$nombreindice = studly_case($nombreindice);

						//$data['options'][] = "\$this->campos['$nombre_columna']['options'] = $nombreindice::pluck('nombre', 'id');";
						if ($indice->isUnique() || $indice->isPrimary() || substr($indice->getName(), -5) == 'index' || substr($indice->getName(), -12) == 'index_unique'){
							continue;
						}

						if (!Schema::hasTable(snake_case($nombreindice))){
							continue;
						}

						$_options = DB::table(snake_case($nombreindice))->get()->all();
						//dd($_options);
						$_columnas = Schema::getColumnListing(snake_case($nombreindice));

						$propiedades['data'] = [snake_case($nombreindice)];

						$url = snake_case($nombreindice);

						if (in_array('id', $_columnas)){
							$propiedades['data'][] = 'id';
						}else{
							$propiedades['data'][] = snake_case($_columnas[0]);
						}

						if (in_array('nombre', $_columnas)){
							$propiedades['data'][] = 'nombre';
						}else{
							$propiedades['data'][] = $_columnas[1];
						}
						
						$propiedades['options'] = [];

						foreach ($_options as $option) {
							$propiedades['options'][] = [$option->{$propiedades['data'][1]}, $option->{$propiedades['data'][2]}];
						}
					}
				}

				$propiedades['url'] = $url;
			}

			$propiedades['validate'] = $validate;


			$campos[$nombre_columna] = $propiedades;
		}
		
		$this->columnas = array_keys($this->columnas_tipos);

		$relaciones = $this->relaciones($this->tabla);

		return [
			'campos' => $campos,
			'relaciones' => $relaciones
		];
	}

	protected function relaciones($tabla){
		$conexion = DB::connection();
		$schema = $conexion->getDoctrineSchemaManager();

		$_tablas = $this->tablas();
		$tablas = [];
		$relaciones = [];
		$modelos = $this->modelos();

		foreach ($_tablas as $table) {
			$indice = $schema->listTableIndexes($table);
			unset($indice['primary']);

			foreach ($indice as $nombre => $ind) {
				if ($ind->isUnique() || $ind->isPrimary() || substr($ind->getName(), -5) == 'index' || substr($ind->getName(), -12) == 'index_unique'){
					unset($indice[$nombre]);
				}

				if ($table == $tabla){
					$tabla_relacion = str_replace([$tabla . '_', '_id', '_foreign'], '', $nombre);
					$model = "";
					foreach ($modelos as $modelo) {
						if (snake_case($modelo[1]) == $tabla_relacion){
							$model = $modelo[0];
						}
					}

					if ($model == ""){
						continue;
					}

					$relaciones[] = [
						'relacion' 		=> 'belongsTo',
						'model' 		=> $model,
						'foreign_key' 	=> '', // $tabla . '_id'
						'local_key' 	=> '' // 'id'
					];
				}

				$tabla_relacion = substr($nombre, 0, strpos($nombre, $tabla . '_id') - 1);
				if (strpos($nombre, $tabla . '_id') !== false) {
					$model = $tabla_relacion;
					foreach ($modelos as $modelo) {
						if (snake_case($modelo[1]) == $tabla_relacion){
							$model = $modelo[0];
						}
					}

					$relaciones[] = [
						'relacion' 		=> 'hasMany',
						'model' 		=> $model,
						'foreign_key' 	=> '', // $tabla . '_id'
						'local_key' 	=> '' // 'id'
					];
				}
			}

			if (!empty($indice)){
				$tablas[$table] = $indice;
			}
		}

		//dd($relaciones, $tablas);
		return $relaciones;
	}

	protected function getInputType($dataType){
		$lookup = array(
			'string'  => 'text',
			'integer' => 'number',
			'float'   => 'number',
			'date'    => 'date',
			'text'    => 'textarea',
			'boolean' => 'checkbox'
		);
		return array_key_exists($dataType, $lookup)
			? $lookup[$dataType]
			: 'text';
	}
}