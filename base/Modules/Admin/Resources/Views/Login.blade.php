<?php
$controller = app('Modules\Admin\Http\Controllers\Controller');
$controller->css[] = 'login.min.css';
$controller->js[] = 'login.js';

$data = $controller->_app();
extract($data);

$html['titulo'] = 'Inicio de Sesión';
?>
<!DOCTYPE html>
<!--[if IE 8]>    <html lang="es" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>    <html lang="es" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="es"><!--<![endif]-->
<head>
	@include('admin::partials.head')
</head><!--/head-->

	<body class="login">
		<div class="logo">
			<label>K A L E E S I</label>
		</div>
		<div class="content">
			{!! Form::open(array('id' => 'formulario', 'url' => 'login')) !!}
				<h3 class="form-title font-green">{{ Lang::get('login.log_in') }}</h3>

				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{{ Lang::get('login.user') }}</label>
					{!! Form::text('nombre', '', ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => Lang::get('login.user')]) !!}
				</div>

				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{{ Lang::get('login.password') }}</label>
					{!! Form::password('password', ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => Lang::get('login.password')]) !!}
				</div>

				<label class="rememberme check mt-checkbox mt-checkbox-outline">
					{!! Form::checkbox('recordar', '1', false) !!}
					{{ Lang::get('login.remember_me') }}
					<span></span>
				</label>
				
				<div class="form-actions" style="text-align: center;">
					{!! Form::button(Lang::get('login.log_in'), ['class' => 'btn green uppercase']) !!}
				</div>
				<a href="{{url('recuperar/')}}">
					<label class="btnrecuperar mt-checkbox mt-checkbox-outline">Olvide mi Contraseña</label> 
				</a>
				
				<div class="create-account">
					<p>{{ date('Y') }} &copy; PintoSoft.</p>
				</div>
			{!! Form::close() !!}
		</div>
		
		@include('admin::partials.footer')
	</body>
</html>
		