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
					<label class="control-label visible-ie8 visible-ie9">Nueva Clave</label>
					<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Usuario" name="nombre" type="text" value="">
					<label class="control-label visible-ie8 visible-ie9">Repita su nueva Clave</label>
					<input class="form-control form-control-solid placeholder-no-fix" autocomplete="off" placeholder="Usuario" name="nombre" type="text" value="">
				</div>
				<div class="form-actions" style="text-align: center;">
					<button class="btn green uppercase" type="button" >Enviar</button>
				</div>
			</form>
			
		</div>
		<div class="create-account">
			<p>2017 &copy; PintoSoft.</p>
		</div>
		@include('admin::partials.footer')
	</body>
</html>
		