@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
	@include('admin::partials.botonera')
	
	@include('admin::partials.ubicacion', ['ubicacion' => ['Administrador', 'Perfiles']])

	@include('admin::partials.modal-busqueda', [
		'titulo' => 'Buscar Usuarios.',
		'columnas' => [
			'Nombre' => '100'
		]
	])
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-6 col-xs-12">
			{!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST']) !!}
				{{ Form::hidden('permisos', '', ['id' => 'permisos']) }}

				{{ Form::bsText('nombre', '', [
					'label' 		=> 'Nombre',
					'placeholder' 	=> 'Nombre',
					'required' 		=> 'required',
					'class_cont' 	=> ''
				]) }}
			{!! Form::close() !!}
		</div>

		<div class="form-group col-sm-6 col-xs-12">
			<label for="arbol">Permisos de Usuario:</label>
			<input id="input_buscar" name="input_buscar" class="form-control" type="text" placeholder="Buscar" value="" /><br />

			<div id="arbol"></div>
		</div>
	</div>
@endsection

@push('css')
<style type="text/css" media="screen">
	#arbol{
		min-height: 200px;
	}
</style>
@endpush