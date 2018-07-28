<?php

namespace Modules\Admin\Model;

use DB;
use Carbon\Carbon; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Model\Historico;

abstract class Modelo extends Model
{
	use SoftDeletes;
	protected $fillable = [];

	protected $hidden = ['created_at', 'updated_at'];
	protected $historico = true;
	public static $_historico = true;
	//protected $dateFormat = 'd/m/Y H:i:s';
	
	public $timestamps = false;

	public $bootstrap = true;
	public $usuario_permisos = false;

	protected $settings = array(
		'exclude'       => array(
			'id',
			'created_at',
			'updated_at',
			'deleted_at',
			'password'
		),
		'extras'    => array(
			'class' => 'form-control', 
			'cont_class' => 'col-lg-3 col-md-4 col-sm-6 col-xs-12'
		),
		'showLabels'    => true
	);

	protected $campos = [];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at'
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public static function boot(){
		parent::boot();

		static::saving(function($model){
			$model->updated_at = Carbon::now()->format('Y-m-d H:i:s');
		});

		//--- Modificando los campos de fecha y hora
		// Creando
		static::creating(function($model) {
			$model->created_at = Carbon::now()->format('Y-m-d H:i:s');
			$model->updated_at = Carbon::now()->format('Y-m-d H:i:s');
		});

		// Actualizando
		static::updating(function($model) {
			$model->updated_at = Carbon::now()->format('Y-m-d H:i:s');
		});

		//--- Creando un registro en el historico de la aplicacion
		// Creado
		static::created(function($model) {
			$model->historico('creado', $model->id);
			return true;
		});

		// Actualizado
		static::updated(function($model) {
			$model->historico('actualizado', $model->id);
			return true;
		});

		// Eliminado
		static::deleted(function($model) {
			$model->historico('eliminado', $model->id);
			return true;
		});
	}

	public function historico($concepto, $id){
		if (!$this->historico || !self::$_historico){
			return;
		}

		$usuario = auth()->user();
		
		$login = is_null($usuario) ? 'Invitado' : $usuario->usuario;
		
		Historico::create([
			'tabla'         => $this->table,
			'concepto'      => $concepto,
			'idregistro'    => $id,
			'usuario'       => $login,
		]);
	}

	 public function formatoFecha($value){
		if ($value == ''){
			return null;
		}

		$formato = preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $value) ? 'Y-m-d' : 'd/m/Y';
		return Carbon::createFromFormat($formato, $value);
	}

	public function generate($elements = [], $options = [])
	{
		$table = $this->getTable();
		
		$this->setSettings($options);
		$data = [];
		
		$wildcards = $this->getSettings('extras');

		if (empty($elements)){
			$elements = array_keys($this->campos);
		}
		
		//foreach ($this->campos as $fieldName => $prop) {
		foreach ($elements as $fieldName) {
			$prop = $this->campos[$fieldName];
			if ($fieldName == '*' || in_array($fieldName, $this->getSettings('exclude'))){
				continue;
			}

			$html = [];
			$value = isset($this->campos[$fieldName]['value']) ? $this->campos[$fieldName]['value'] : null;

			if (empty($value)){
				$value = $this->{$fieldName};
				if (empty($value)){
					$value = null;
				}
			}

			if (!isset($prop['id'])){
				$prop['id'] = $fieldName;
			}

			if (isset($prop['type'])) {
				$tipo = $prop['type'];
			}

			if (!isset($prop['class'])){
				$prop['class'] = $wildcards['class'];
			}elseif (isset($wildcards['class'])) {
				$prop['class'] .= ' ' . $wildcards['class'];
			}

			$required = false;
			if (isset($prop['required']) && $prop['required']) {
				$required = true;
				$prop['required'] = 'required';
			}

			unset($prop['type'], $prop['value'], $prop['options'], $prop['label']);

			if (empty($tipo)) {
				$dataType = 'string';
				$tipo = $this->getInputType($dataType);
			}

			if ($this->getContentBefore($fieldName)){
				$html[] = $this->getContentBefore($fieldName);
			}

			$label = '';

			if ($this->getSettings('showLabels')) {
				$label = \Form::label($fieldName, $this->getLabelText($fieldName) . ':', $required ? ['class' => 'required'] : []);
			}
			/*
			hidden
			checkbox
			radio
			date
			time
			textarea
			number
			select
			*/
			switch ($tipo) {
				case 'hidden':
					$label = '';
					$html[] = \Form::input($tipo, $fieldName, $value, $prop);
					
					break;
				case 'checkbox':
					$label = '';
					if ($this->getSettings('showLabels')) {
						$html[] = "<label class='checkbox'>";
					}
					$html[] = \Form::checkbox($fieldName, null, null, $prop) . $this->getLabelText($fieldName);
					if ($this->getSettings('showLabels')) {
						$html[] = "</label>";
					}
					break;
				case 'radio':
					$label = '';
					if ($this->getSettings('showLabels')) {
						$html[] = "<label class='radio'>";
					}
					$html[] = \Form::radio($fieldName, null, null, $prop) . $this->getLabelText($fieldName);
					if ($this->getSettings('showLabels')) {
						$html[] = "</label>";
					}
					break;
				case 'number':
					$html[] = $label . \Form::number($fieldName, $value, $prop);
					break;
				case 'date':
					$html[] = $label . \Form::input('date', $fieldName, date('Y-m-d', strtotime($value)), $prop);
					break;
				case 'time':
					$html[] = $label . \Form::input('time', $fieldName, date('H:i:s', strtotime($value)), $prop);
					break;
				case 'textarea':
					$prop['rows'] = 6;
					$html[] = $label . \Form::textarea($fieldName, $value, $prop);
					break;
				case 'select':
					if (!isset($prop['placeholder'])){
						$prop['placeholder'] = '- Seleccione';
					}

					if (isset($prop['url'])){
						if ($this->getSettings('showLabels')) {
							$labelText = $this->getLabelText($fieldName) . ':';
							

							if ($this->permisologia($prop['url'] . '/nuevo')){
								$labelText .= ' <i class="fa fa-plus" data-ele="' . $fieldName . '" data-url="' . url($prop['url'] . '/nuevo') . '" title="Crear un ' . $this->getLabelText($fieldName) . '"></i>';
							}
							
							if ($this->permisologia($prop['url'] . '/cambiar')){
								$labelText .= ' <i class="fa fa-pencil disabled" data-ele="' . $fieldName . '" data-url="' . url($prop['url'] . '/cambiar') . '" title="Modificar ' . $this->getLabelText($fieldName) . '"></i>';
							}
							unset($prop['url']);
							$label = '<label for="' . $fieldName . '" ' . ($required ? 'class="required"' : '') . '>' . $labelText . '</label>';
						}
					}

					$options = isset($this->campos[$fieldName]['options']) ? $this->campos[$fieldName]['options'] : [];

					$html[] = $label . \Form::select($fieldName, $options, $value, $prop);
					break;
				default:
					$html[] = $label . \Form::input($tipo, $fieldName, $value, $prop);
					break;
			}

			if ($this->getContentAfter($fieldName)){
				$html[] = $this->getContentAfter($fieldName);
			}
			
			if(isset($prop['cont_class'])){
				$data[] = '<div class="form-group ' . $prop['cont_class'] . '">' . 
					trim(implode(PHP_EOL, $html)) . 
				'</div>';
			}elseif(isset($wildcards['cont_class'])){
				$data[] = '<div class="form-group ' . $wildcards['cont_class'] . '">' . 
					trim(implode(PHP_EOL, $html)) . 
				'</div>';
			}else{
				$data[] = trim(implode(PHP_EOL, $html));
			}
		}

		return trim(implode(PHP_EOL, $data));
	}

	protected function getFields($table){
		$field_names = [];
		$columns = DB::select("SHOW COLUMNS FROM `" . strtolower($table) . "`");
		foreach ($columns as $c) {
			$field = $c->Field;
			$field_names[$field] = $field;
		}
		dd($field_names);
		return $field_names;
	}

	protected function getLabelText($fieldName){
		$label = $this->campos[$fieldName]['label'];
		
		if (isset($label) AND !empty($label)) {
			return $label;
		}
		return ucwords(str_replace("_", " ", $fieldName));
	}

	protected function getContentBefore($fieldName){
		$content = isset($this->campos[$fieldName]['content_before']) ? $this->campos[$fieldName]['content_before'] : null;
		$wildcardContent = $this->getSettings('extras', 'content_before');
		if (isset($wildcardContent) AND !empty($wildcardContent)) {
			$content = (isset($content) AND !empty($content)) ? array_push($content, $wildcardContent) : $wildcardContent;
		}
		
		if (isset($content) AND !empty($content)) {
			return $content;
		}
	}

	protected function getContentAfter($fieldName){
		$content = isset($this->campos[$fieldName]['content_after']) ? $this->campos[$fieldName]['content_after'] : null;
		$wildcardContent = $this->getSettings('extras', 'content_after');
		if (isset($wildcardContent) AND !empty($wildcardContent)) {
			$content = (isset($content) AND !empty($content)) ? array_push($wildcardContent, $content) : $wildcardContent;
		}
		if (isset($content) AND !empty($content)) {
			return $content;
		}
	}

	protected function getInputType($dataType){
		$lookup = array(
			'string'  => 'text',
			'float'   => 'text',
			'date'    => 'text',
			'text'    => 'textarea',
			'boolean' => 'checkbox'
		);
		return array_key_exists($dataType, $lookup)
			? $lookup[$dataType]
			: 'text';
	}

	protected function setSettings($options){
		$this->settings = array_merge($this->settings, $options);
	}

	protected function getSettings(){
		$stngs = $this->settings;
		foreach (func_get_args() as $arg) {
			if ( ! is_array($stngs) OR ! is_scalar($arg) OR ! isset($stngs[$arg])) {
				return [];
			}
			$stngs = $stngs[$arg];
		}
		return $stngs;
	}

	public function permisologia($ruta = '') {
		$usuario = auth()->user();
		if (strtolower($usuario->super) === 's') {
			return true;
		}

		if ($this->usuario_permisos === false){
			$this->usuario_permisos = $usuario->permisos();
		}
		
		if ($ruta === '') {
			$ruta = $this->ruta();
		}

		$ruta = preg_replace('/^' . \Config::get('admin.prefix') . '\//i', '', $ruta);

		return $this->usuario_permisos->search($ruta);
	}
}