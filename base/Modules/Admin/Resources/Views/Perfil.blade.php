@extends('admin::layouts.default')
@section('content')
	@include('admin::partials.ubicacion', ['ubicacion' => ['Perfil de Usuario']])

	<div class="row">
		<div class="profile-sidebar col-md-3">
			<div class="portlet light profile-sidebar-portlet">
				<div class="mt-element-overlay">
					<div class="col-md-12">
						<div class="mt-overlay-6">
							<img id="foto" src="{{ url('public/img/usuarios/' . (is_file('public/img/usuarios/' . $usuario->foto) ? $usuario->foto : 'user.png')) }}" class="img-responsive" alt="" />
							<div class="mt-overlay">
								<h2> </h2>
								<p>
									<form id="formulario_imagen" enctype="multipart/form-data"  autocomplete="off">
										<input id="upload" name="foto" type="file"/>
										<a href="#" id="upload_link" class="mt-info uppercase btn default btn-outline">Cargar Foto de Perfil</a>
									</form>
								</p>
							 </div>
						</div>
					</div>
				</div>

				<div class="profile-usertitle">
					<div class="profile-usertitle-name"> {{ $usuario->nombre }} <span id="ape"> </span> </div>
					<div class="profile-usertitle-job"> {{ $usuario->usuario }} </div>
				</div>
				<br />
			</div>
		</div>
		<div class="profile-content col-md-9">
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light ">
						<div class="portlet-title tabbable-line">
							<div class="caption caption-md">
								<i class="icon-globe theme-font hide"></i>
								<span class="caption-subject font-blue-madison bold uppercase">Perfil de Usuario</span>
							</div>
							<ul class="nav nav-tabs">
								<li class="active">
									<a href="#tab_1_1" data-toggle="tab">Informacion Personal</a>
								</li>
								<li>
									<a href="#tab_1_2" data-toggle="tab">Cambio de Clave</a>
								</li>
							</ul>
						</div>
						<div class="portlet-body">
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1_1">
									<form id="formulario" role="form" autocomplete="off">
										<input type="hidden" name="id" id="id" value="{{ $usuario->id }}">

										{{ Form::bsText('dni', $usuario->dni, [
											'label' 		=> 'C&eacute;dula',
											'placeholder' 	=> 'C&eacute;dula del usuario',
											'required' 		=> 'required',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsText('nombre', $usuario->nombre, [
											'label' 		=> 'Nombre',
											'placeholder' 	=> 'Nombre del usuario',
											'required' 		=> 'required',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsText('apellido', $usuario->apellido, [
											'label' 		=> 'Apellido',
											'placeholder' 	=> 'Apellido del usuario',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsSelect('sexo', [
												'f' => 'Femenino',
												'm' => 'Masculino',
											], $usuario->sexo, [
											'label' 		=> 'sexo',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsSelect('edo_civil', [
												'so' => 'Soltero',
												'di' => 'Divorciado',
												'vi' => 'Viudo',
												'ca' => 'Casado',
												'co' => 'Concubino',
											], $usuario->edo_civil, [
											'label' 		=> 'Estado Civil',
											'class_cont' 	=> 'col-sm-12'
										]) }}


										{{ Form::bsText('correo', $usuario->correo, [
											'label' 		=> 'Correo',
											'placeholder' 	=> 'Correo Electronico del Usuario',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsText('telefono', $usuario->telefono, [
											'label' 		=> 'Tel&eacute;fono',
											'placeholder' 	=> 'Tel&eacute;fono del Usuario',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsText('facebook', $usuario->facebook, [
											'label' 		=> 'Facebook',
											'placeholder' 	=> 'Facebook del Usuario',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										 {{ Form::bsText('instagram', $usuario->instagram, [
											'label' 		=> 'Instagram',
											'placeholder' 	=> 'Instagram del Usuario',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsText('twitter', $usuario->twitter, [
											'label' 		=> 'Twitter',
											'placeholder' 	=> 'Twitter del Usuario',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										<div class="form-group col-sm-12">
											<label class="control-label">Dirección</label>
											<textarea id="direccion" name="direccion" class="form-control" placeholder="Dirección" rows="3" style="width: 100%; height: 70px;">{{ $usuario->direccion }}</textarea>
										</div>

										<div class="margiv-top-10">
											<input type="submit" class="btn green" value="Guardar">
										</div>
									</form>
								</div>

								<div class="tab-pane" id="tab_1_2">
									<form id="formulario_clave">
										{{ Form::bsPassword('password', '', [
											'label' 		=> 'Nueva Contrase&ntilde;a',
											'placeholder' 	=> 'Nueva Contrase&ntilde;a',
											'required' 		=> 'required',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										{{ Form::bsPassword('password_confirmation', '', [
											'label' 		=> 'Repita Nueva Contrase&ntilde;a',
											'placeholder' 	=> 'Repita Nueva Contrase&ntilde;a',
											'required' 		=> 'required',
											'class_cont' 	=> 'col-sm-12'
										]) }}

										<div class="margin-top-10">
											<input type="submit" class="btn green" value="Guardar">
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('css')
<style type="text/css">
	#upload_link{
	text-decoration:none;
	}
	#upload{
	display:none
	}
	.mt-element-overlay .mt-overlay-6 {
		background: #003f8c none repeat scroll 0 0;
	}
	.mt-overlay-6 img{
		min-height: 250px;
	}
</style>
@endpush