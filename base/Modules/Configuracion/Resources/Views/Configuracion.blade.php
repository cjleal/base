	@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
	@include('admin::partials.ubicacion', ['ubicacion' => ['General', 'Configuración']])
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
			 <h4><strong>Configuración</strong></h4>
			 <div class="tabbable-line tabs-below">
				<ul class="nav nav-tabs nav-stacked" role="tablist">
					<li role="presentation" class="active">
						<a href="#homeDatos" aria-controls="homeDatos" data-toggle="tab"> General </a>
					</li>
					<li>
						<a href="#prueba" aria-controls="prueba" data-toggle="tab"> Email</a>
					</li>
				</ul>
			 </div>
		</div>

		<form id="formulario" name="formulario" enctype="multipart/form-data" role="form" autocomplete="off">
			<div class="col-md-9">
				 <div class="tab-content">
					<div role="tabpanel" class="tab-pane active " id="homeDatos">
						<div class="form-group">
							{{ Form::bsText('nombre', $controller->conf('nombre'), [
								'label'     	=> 'nombre',
								'placeholder'   => 'Nombre',
								'class_cont'  	=> 'col-sm-12'
							]) }}
						</div>

						<div class="form-group">
							{{ Form::bsSelect('format_fecha', [
								'd/m/Y' 		=> 'd/m/Y',
								'd-m-Y' 		=> 'd-m-Y',
								'Y-m-d' 		=> 'Y-m-d',
								'Y/m/d' 		=> 'Y/m/d',
								'D, dd M Y' 	=> 'D, dd M Y',
								'D, d M y' 		=> 'D, d M y',
								'DD, dd-M-y' 	=> 'DD, dd-M-y',
								'D, d M y' 		=> 'D, d M y',
							], $controller->conf('format_fecha'), [
								'label'     	=> 'formato de fecha',
								'class_cont'  	=> 'col-sm-12'
							]) }}
						</div>

						<div class="form-group">
							{{ Form::bsSelect('miles', [
								',' => ',',
								'.' => '.',
							], $controller->conf('miles'), [
								'label'     	=> 'Separador de miles',
								'class_cont'  	=> 'col-sm-12'
							]) }}
						</div>

						<div class="form-group">
							<div  id="log" class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px; margin-left: 16px;">
									<img id="cargar_logo" src="{{ url('public/img/logo/logo.png') }}">
								</div>
								<div>
									<span class="btn btn-default btn-file" style=" margin-left: 16px;">
										<span class="fileinput-new" >Seleccionar Imagen</span>
										<span class="fileinput-exists">Cambiar</span>
										<input type="file" name="logo" id="upload">
									</span>
									<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" >Eliminar</a>
							 	</div>
							 </div>
						</div> 
						<div class="form-group">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100px; height: 50px; margin-left: 16px;">
									<img id="cargar_logo" src="{{ url('public/img/login_logo/login_logo.png') }}">
								</div>
								<div>
									<span class="btn btn-default btn-file" style=" margin-left: 16px;">
										<span class="fileinput-new">Seleccionar Imagen</span>
										<span class="fileinput-exists">Cambiar</span>
										<input type="file" name="login_logo" id="login_upload">
									</span>
									<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
								</div>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane " id="prueba">
						 <div class="form-group">
							{{ Form::bsText('email',$controller->conf('email'), [
								'label'     	=> 'email',
								'class_cont'  	=> 'col-sm-12'
							]) }}
						 </div>
						 <div class="form-group">
							{{ Form::bsText('email_name',$controller->conf('email_name'), [
								'label'     	=> 'El correo electrónico enviado por el nombre',
								'class_cont'  	=> 'col-sm-12'
							]) }}  
						 </div>
						 <div class="form-group">
							{{ Form::bsText('email_prueba',$controller->conf('email_prueba'), [
								'label'     	=> 'Enviar un mensaje de prueba a',
								'class_cont'  	=> 'col-sm-12'
							]) }}  
						 </div>
					</div>
				 </div>
			</div>
			<input id="guardo" type="submit" class="btn blue tooltips" value="Guardar" />
		</form>
	 </div>
</div>
@endsection