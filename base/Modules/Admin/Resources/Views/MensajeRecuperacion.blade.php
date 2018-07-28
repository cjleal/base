<?php
$controller = app('Modules\Admin\Http\Controllers\Controller');
$controller->css[] = 'recuperar.css';
$controller->js[] = 'recuperar.js';

$data = $controller->_app();
extract($data);

$html['titulo'] = 'Mensajes de Recuperación de Contraseña';
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
			
				<h3 class="form-title font-green">{{$title}}</h3>

				<div class="panel panel-default">
					<div class="panel-body">
						<p>Estimado Usuario Reciba un cordial saludo, 
		    				el presente correo es para notificarle que sus datos fueron 
		    				recuperados exitosamente!!!. su contraseña temporal es: {{ $claveTemporal }}, recuerde cambiarla despues de ingresar al sistema. Muchas gracias!!! </p>
					</div>
				</div>
				
			
			
		</div>
		@include('admin::partials.footer')
	</body>
</html>
		