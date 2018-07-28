<?php
$controller = app('Modules\Admin\Http\Controllers\Controller');
$controller->css[] = 'recuperar.css';
$controller->js[] = 'recuperar.js';

$data = $controller->_app();
extract($data);

$html['titulo'] = 'Recuperar Contraseña';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	@include('admin::partials.head')
</head><!--/head-->

	<body class="login">
		<div class="logo">
			<label>K A L E E S I</label>
		</div>
		<div class="content">
			<form accept-charset="UTF-8" id="formulario" action="{{ URL::current() }}" method="POST">
				{{csrf_field()}}
				<h3 class="form-title font-green">Seguridad</h3>

				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">Usuario</label>
					<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Usuario" name="nombre" type="text" value="">
				</div>
				<div class="preguntas">
					<legend>Preguntas de Seguridad</legend>
					<div class="form-group pregunta1 col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="respuesta_pri"></label>
						<input placeholder="Primera respuesta" id="respuesta_pri" class="form-control" name="respuesta_pri" type="text">
					</div>
					<div class="form-group pregunta2 col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label for="respuesta_seg"></label>
						<input placeholder="Segunda respuesta" id="respuesta_seg" class="form-control" name="respuesta_seg" type="text">
					</div>
				</div>
				<div class="form-actions" style="text-align: center;">
					<span class="btn green uppercase enviar" accion="">Enviar</span>
					<!--<button class="btn green uppercase" type="button" accion="">Enviar</button>-->
				</div>
			</form>
			
		</div>
		<div class="create-account">
			<p>2017 &copy; PintoSoft.</p>
		</div>
		@include('admin::partials.footer')
	</body>
</html>
		