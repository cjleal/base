<?php

namespace Modules\Admin\Model;


use Illuminate\Database\Eloquent\Model;

abstract class ModelValidate extends Model
{
	protected $errors;
	protected static $rules = array();
	protected static $messages = array();
	protected $validator;

	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);
		$this->validator = \App::make('validator');
	}

	
}