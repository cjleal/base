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

	<!-- BEGIN SAMPLE FORM PORTLET-->
	<div class="portlet light ">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-table font-dark"></i>
				<span class="caption-subject font-dark sbold uppercase">Estructura</span>
			</div>
		</div>

		<div class="portlet-body form row skin-square">
			<div class="form-group col-md-12">
				<input id="nombre_tabla" name="nombre_tabla" class="form-control" placeholder="Nombre de la Tabla" type="text" value="" />
			</div>
			<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<label>
					<input type="checkbox" name="increments" /> ID
				</label>
			</div>
			<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<label>
					<input type="checkbox" name="timestamps" /> Timestamps
				</label>
			</div>
			<div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<label>
					<input type="checkbox" name="softDeletes" /> SoftDeletes
				</label>
			</div>
			<div class="col-md-12">
				<table id="fields" class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th style="width: 2%;"><i class="fa fa-plus"></i></th>
							<th style="width: 39%;">Nombre</th>
							<th style="width: 24%;">Tipo</th>
							<th style="width: 10%;">Tama&ntilde;o</th>
							<th style="width: 10%;">Default</th>
							<th style="width: 5%; text-align: center;">Nulo?</th>
							<th style="width: 5%; text-align: center;">Unico</th>
							<th style="width: 5%; text-align: center;"><i class="fa fa-times"></i></th>
						</tr>
					</thead>

					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</form>
@endsection

@push('js')
<script type="text/x-tmpl" id="tmpl-table-field">
<tr>
	<td class="tr-move" style="width: 2%"><i class="fa fa-bars"></i></td>
	<td style="width: 39%"><input name="name[]" class="form-control" type="text"></td>
	<td style="width: 24%">
		<select name="type[]" class="form-control">
			<option value="">- Seleccione</option>

			<optgroup label="Comunes">
				<option value="string">string</option>
				<option value="integer">integer</option>
				<option value="decimal">decimal</option>
				<option value="date">date</option>
				<option value="dateTime">dateTime</option>
				<option value="text">text</option>
			</optgroup>

			<optgroup label="Numericos">
				<option value="smallInteger">smallInteger</option>
				<option value="tinyInteger">tinyInteger</option>
				<option value="mediumIncrements">mediumIncrements</option>
				<option value="bigInteger">bigInteger</option>

				<option value="float">float</option>
				<option value="double">double</option>

				<option value="unsignedInteger">unsignedInteger</option>
				<option value="unsignedTinyInteger">unsignedTinyInteger</option>
				<option value="unsignedSmallInteger">unsignedSmallInteger</option>
				<option value="unsignedMediumInteger">unsignedMediumInteger</option>
				<option value="unsignedBigInteger">unsignedBigInteger</option>

				<option value="smallIncrements">smallIncrements</option>
				<option value="increments">increments</option>
				<option value="mediumInteger">mediumInteger</option>
				<option value="bigIncrements">bigIncrements</option>
			</optgroup>

			<optgroup label="Cadenas">
				<option value="char">char</option>
				<option value="mediumText">mediumText</option>
				<option value="longText">longText</option>
				<option value="json">json</option>
				<option value="jsonb">jsonb</option>
				<option value="rememberToken">rememberToken</option>
			</optgroup>

			<optgroup label="Redes">
				<option value="ipAddress">ipAddress</option>
				<option value="macAddress">macAddress</option>
			</optgroup>

			<optgroup label="Fechas">
				<option value="dateTimeTz">dateTimeTz</option>
				<option value="nullableTimestamps">nullableTimestamps</option>
				<option value="time">time</option>
				<option value="timeTz">timeTz</option>
				<option value="timestamp">timestamp</option>
				<option value="timestampTz">timestampTz</option>
				<option value="timestamps">timestamps</option>
				<option value="timestampsTz">timestampsTz</option>
			</optgroup>

			<optgroup label="Logicos">
				<option value="binary">binary</option>
				<option value="boolean">boolean</option>
			</optgroup>

			<optgroup label="Otros">
				<option value="uuid">uuid</option>
				<option value="morphs">morphs</option>
				<option value="enum">enum</option>
				<option value="softDeletes">softDeletes</option>
			</optgroup>
		</select>
	</td>
	<td style="width: 10%"><input name="length[]" class="form-control" type="text"></td>
	<td style="width: 10%"><input name="default[]" class="form-control" type="text"></td>
	<td style="width: 5%"><input name="null[]" type="checkbox" value="1"></td>
	<td style="width: 5%"><input name="unique[]" type="checkbox" value="1"></td>
	<td style="width: 5%"><i class="fa fa-times"></i></td>
</tr>
</script>
@endpush