@extends('admin::layouts.default')
@section('content')
	@include('admin::partials.botonera')

	
	<ul class="page-breadcrumb breadcrumb">
		<li>
			<a href="{{ url('/') }}">Inicio</a><i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Aplicacion</span> <i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Configuraci&oacute;n</span>
		</li>
	</ul>
	
	<div class="row">
		{!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST' ]) !!}
		
		<div class="mt-element-overlay col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="mt-overlay-3 mt-overlay-3-icons">
				<img src="{{ url('public/img/logos/logo.png') }}">
				<div class="mt-overlay">
					<h2>Overlay Title</h2>
					<ul class="mt-info">
						<li>
							<a class="btn default btn-outline" href="javascript:;">
								<i class="icon-magnifier"></i>
							</a>
						</li>
						<li>
							<a class="btn default btn-outline" href="javascript:;">
								<i class="icon-link"></i>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="kv-avatar center-block" style="width:200px">
				<input id="avatar-2" name="avatar-2" type="file" class="file-loading">
			</div>
		</div>
		
		{!! Form::close() !!}
	</div>
@endsection

@push('css')
<style type="text/css">

</style>
@endpush