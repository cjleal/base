@extends('admin::layouts.default')

@section('content-top')
	<div id="botonera">
		<div class="msj-botonera bg-red bg-font-red text-left"></div>
		<div class="btn-group btn-group-solid">
			<button id="limpiar" class="btn default tooltips" data-container="body" data-placement="top" data-original-title="{{ Lang::get('backend.btn_group.clean.title') }}">
				<i class="fa fa-file-o" aria-hidden="true"></i>
				<span class="visible-lg-inline visible-md-inline">{{ Lang::get('backend.btn_group.clean.btn') }}</span>
			</button>

			<button id="guardar" class="btn blue tooltips" data-container="body" data-placement="top" data-original-title="{{ Lang::get('backend.btn_group.save.title') }}">
				<i class="fa fa-floppy-o" aria-hidden="true"></i>
				<span class="visible-lg-inline visible-md-inline">{{ Lang::get('backend.btn_group.save.btn') }}</span>
			</button>

			<button id="eliminar" class="btn red tooltips" data-container="body" data-placement="top" data-original-title="{{ Lang::get('backend.btn_group.remove.title') }}">
				<i class="fa fa-trash" aria-hidden="true"></i>
				<span class="visible-lg-inline visible-md-inline">{{ Lang::get('backend.btn_group.remove.btn') }}</span>
			</button>
		</div>
	</div>

	@include('admin::partials.ubicacion', ['ubicacion' => ['Generators', 'Tables']])
@endsection

@section('content')
<form id="formulario">	
	<div class="row">
		{{ Form::bsSelect('modulo', $controller->modulos(), '', [
			'label' => 'Modulo'
		]) }}

		{{ Form::bsSelect('tabla', $controller->tablas(), '', [
			'label' => 'Tabla'
		]) }}
	</div>

	<div id="general" class="row">
		<h2>General</h2>
		<div class="form-group skin-square col-xs-12">
			<label>
				<input type="checkbox" name="forzar" checked="checked" /> Forzar la Creaci&oacute;n de Archivos
			</label>

			<label>
				<input type="checkbox" name="estructura" /> Crear archivos sin Contenido
			</label>

			<label>
				<input type="checkbox" name="timestamps" checked="checked" /> Timestamps
			</label>

			<label>
				<input type="checkbox" name="softDeletes" checked="checked" /> softDeletes
			</label>
		</div>
		<h4>Archivos</h4>

		<div class="form-group skin-square col-xs-12">
			<label>
				<input type="checkbox" name="controller" checked="checked" /> Controller
			</label>
		
			<label>
				<input type="checkbox" name="request" checked="checked" /> Request
			</label>
		
			<label>
				<input type="checkbox" name="view" checked="checked" /> View
			</label>
		
			<label>
				<input type="checkbox" name="model" checked="checked" /> Model
			</label>
		
			<label>
				<input type="checkbox" name="css" checked="checked" /> Css
			</label>
		
			<label>
				<input type="checkbox" name="js" checked="checked" /> Js
			</label>

			<label>
				<input type="checkbox" name="route" checked="checked" /> Route
			</label>
		</div>
	</div>

	<div class="row">
		<h2>
			Modelo
			<button id="agregar_campo" class="btn blue">
				<i class="fa fa-plus" aria-hidden="true"></i>
			</button>
		</h2>
		<div id="modelo">
			
		</div>
	</div>
	<div class="row">
		<h2>
			Relaci&oacute;n Modelo
		</h2>
		<table id="relaciones" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th style="width: 2%;"><i class="fa fa-plus"></i></th>
					<th style="width: 25%;">Relaci&oacute;n</th>
					<th style="width: 25%;">Modelo</th>
					<th style="width: 23%;">Llave Foranea</th>
					<th style="width: 23%;">Llave Local</th>
					<th style="width: 2%; text-align: center;"><i class="fa fa-times"></i></th>
				</tr>
			</thead>

			<tbody></tbody>
		</table>
	</div>
</form>

<div id="modal-propiedades" class="modal modal-busqueda fade" tabindex="-1" role="dialog">
	<div class="modal-dialog container">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Propiedades</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="type">Tipo: </label>
						<select id="type" class="form-control">
							<option value="hidden">Oculto</option>
							<optgroup label="Comun">
								<option value="text">Texto</option>
								<option value="number">Numerico</option>
								<option value="select">Select</option>
								<option value="date">Fecha</option>
								<option value="textarea">Textarea</option>
								<option value="checkbox">Booleano</option>
								<option value="time">Tiempo</option>
								<option value="datetime">Fecha y Hora</option>
							</optgroup>
							<optgroup label="Datos personales">
								<option value="dni">Dni</option>
								<option value="rif">RIF</option>
							</optgroup>
						</select>
					</div>
				</div>
				
				<div class="row">
					<h3 class="col-xs-12">Propiedades</h3>
					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="id">id: </label>
						<input id="id" class="form-control" type="text" placeholder="id" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="name">Nombre: </label>
						<input id="name" class="form-control" type="text" placeholder="Nombre" />
					</div>


					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="label">Label: </label>
						<input id="label" class="form-control" type="text" placeholder="label" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="placeholder">placeholder: </label>
						<input id="placeholder" class="form-control" type="text" placeholder="placeholder" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="cont_class">Clase del Contenedor: </label>
						<input id="cont_class" class="form-control" type="text" placeholder="Clase del Contenedor" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="url">URL: </label>
						<input id="url" class="form-control" type="text" placeholder="Aplica a los campos tipo Select" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="data">data: </label>
						<input id="data" class="form-control" type="text" placeholder="Aplica a los campos tipo Select (tablaR,id,campo)" />
					</div>
				</div>

				<div class="row">
					<h3 class="col-xs-12">Validación: <label><input id="required" type="checkbox"  /> Required?</label></h3>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="validate">Validación: </label>
						<select id="validate" class="form-control">
							<option value="">- Seleccione</option>
							<option value="integer">Integer</option>
							<option value="string">String</option>

							<option value="usuario">usuario</option>
							<option value="nombre">nombre</option>
							<option value="telefono">telefono</option>
							<option value="password">password</option>

							<option value="alpha">Alpha</option>
							<option value="alpha_dash">Alpha Dash</option>
							<option value="alpha_num">Alpha Numeric</option>
							<option value="boolean">Boolean</option>
							<option value="date">Date</option>
							<option value="email">Email</option>
							<option value="ip">IP</option>
							<option value="json">Json</option>
							<option value="json">Json</option>
							<option value="numeric">Numeric</option>
							<option value="url">Url</option>
						</select>
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="min">Min: </label>
						<input id="min" class="form-control" type="number" placeholder="Cantidad de Caracteres Minimos" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="max">Max: </label>
						<input id="max" class="form-control" type="number" placeholder="Cantidad de Caracteres Maximos" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="unique">Unico: </label>
						<input id="unique" class="form-control" type="text" placeholder="Consulta que lo identifica como unico" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="regex">Expresion Regular: </label>
						<input id="regex" class="form-control" type="text" placeholder="Expresion Regular" />
					</div>

					<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<label for="date_format">Formato de Fecha: </label>
						<input id="date_format" class="form-control" type="text" placeholder="d/m/Y" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button id="btn-guardar" type="button" class="btn btn-primary">Guardar</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('js')
<script id="tmpl-field" type="text/x-tmpl">
	{% for (var i = 0; i < o.length; i++) { %}
	<div class="form-group {%=o[i].cont_class%} {% if (o[i].required) { %} required {% } %}" data-prop="{%=JSON.stringify(o[i])%}">
		{% if (o[i].type == 'text' || o[i].type == 'number' || o[i].type == 'date') { %}
			<label>{%=o[i].label%}:</label>
			<input class="form-control" type="{%=o[i].type%}" placeholder="{%=o[i].placeholder%}" />
		{% } else if (o[i].type == 'select') { %}
			<label>{%=o[i].label%}:</label>
			<select class="form-control" id="{%=o[i].type%}" name="{%=o[i].type%}">
				<option selected="selected" value="">- Seleccione</option>
				{% for (var j=0; j < o[i].options.length; j++) { %}
					<option value="o[i].options[j][0]">{%=o[i].options[j][1]%}</option>
				{% } %}
			</select>
		{% } else if (o[i].type == 'textarea') { %}
			<label>{%=o[i].label%}:</label>
			<textarea class="form-control textarea" placeholder="{%=o[i].placeholder%}" rows="5"></textarea>
		{% } else if (o[i].type == 'checkbox') { %}
			<label>
				<input type="checkbox" checked="checked" /> {%=o[i].label%}
			</label>
		{% } %}
	</div>
	{% } %}
</script>

<script id="tmpl-relacion" type="text/x-tmpl">
{% for (var i = 0; i < o.length; i++) { %}
<tr>
	<td class="tr-move" style="width: 2%"><i class="fa fa-bars"></i></td>
	<td style="width: 25%;">
		<select name="relacion[]" class="form-control">
			<option value="hasOne" {% if (o[i].relacion == 'hasOne') { %} selected="selected" {% } %}>One To One (hasOne)</option>
			<option value="hasMany" {% if (o[i].relacion == 'hasMany') { %} selected="selected" {% } %}>One To Many (hasMany)</option>
			<option value="belongsTo" {% if (o[i].relacion == 'belongsTo') { %} selected="selected" {% } %}>One To Many (Inverse - belongsTo)</option>
			<option value="belongsToMany" {% if (o[i].relacion == 'belongsToMany') { %} selected="selected" {% } %}>Many To Many (belongsToMany)</option>
		</select>
	</td>
	<td style="width: 25%">
		<select name="model[]" class="form-control">
			{% for (var j = 0; j < o[i].models.length; j++) { %}
			<option value="{%=o[i].models[j][0]%}" {% if (o[i].model == o[i].models[j][0]) { %} selected="selected" {% } %}>{%=o[i].models[j][1]%}</option>
			{% } %}
		</select>
	</td>
	<td style="width: 23%"><input name="foreign_key[]" class="form-control" type="text" value="{%=o[i].foreign_key%}" /></td>
	<td style="width: 23%"><input name="local_key[]" class="form-control" type="text" value="{%=o[i].local_key%}" /></td>
	<td style="width: 2%"><i class="fa fa-times"></i></td>
</tr>
{% } %}
</script>
@endpush