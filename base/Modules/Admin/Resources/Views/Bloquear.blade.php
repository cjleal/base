<!DOCTYPE html>
<!--[if IE 8]>    <html lang="es" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>    <html lang="es" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="es"> <!--<![endif]-->
<head>
		<meta charset="utf-8" />
		<title>Pantalla de Bloqueo</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<meta name="csrf-token" content="{{ csrf_token() }}"/>

		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" />

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

		<!-- Font awesome -->
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css" />

		<!-- Animate -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.css" />

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.css" />
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.buttons.min.css" />
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.nonblock.min.css" />


		<link rel="stylesheet" type="text/css" href="{{ asset('public/css/components.min.css') }}" />
		<link rel="stylesheet" type="text/css"  href="{{ asset('public/css/plugins.min.css') }}" />

		<link rel="stylesheet" type="text/css" href="{{ asset('Modules/Admin/Assets/css/lock.min.css') }}" />
		<link rel="shortcut icon" href="favicon.ico" />
	</head>
	<body class="login">
		<div class="page-lock">
			<div class="page-logo">
				<a class="brand" href="{{ url(\Config::get('admin.prefix')) }}">
					<img src="{{ asset('public/img/logos/logo_blanco.png') }}" alt="Logo" />
				</a>
			</div>
			<div class="page-body">
				<div class="lock-head"> Bloqueado </div>
				<div class="lock-body">
					<div class="pull-left lock-avatar-block">
						<img src="{{ asset('public/img/usuarios/' . \Auth::user()->usuario . '.jpg') }}" class="lock-avatar">
					</div>
					{!! Form::open(array('id' => 'formulario', 'url' => 'login', 'class' => 'lock-form pull-left')) !!}
						<input id="nombre" name="nombre" type="hidden" value="{{ \Auth::user()->usuario }}" />
						<h4>{{ \Auth::user()->nombre }}</h4>
						<div class="form-group">
							{!! Form::password('password', ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => Lang::get('login.password')]) !!}
						</div>
						<div class="form-actions">
							{!! Form::button(Lang::get('login.log_in'), ['class' => 'btn red uppercase']) !!}
						</div>
					{!! Form::close() !!}
				</div>
				<div class="lock-bottom">
					<a href="{{ url('login/salir') }}">&iquest;No eres {{ \Auth::user()->nombre }}?</a>
				</div>
			</div>
			<div class="page-footer-custom"> 2016 &copy; Camaleon24. </div>
		</div>

		<!--[if lt IE 9]>
		<script src="../assets/global/plugins/respond.min.js"></script>
		<script src="../assets/global/plugins/excanvas.min.js"></script> 
		<![endif]-->
		

		<!-- jQuery -->
		@if(preg_match('/(?i)msie [5-8]/',$_SERVER['HTTP_USER_AGENT']))
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		@else
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		@endif

		<!-- Bootstrap Js -->
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

		<!-- Form ajax -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

		<!-- PNotify -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.buttons.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.0.0/pnotify.nonblock.min.js"></script>

		<script type="text/javascript" src="{{ asset('public/js/init.js') }}"></script>
		<script type="text/javascript" src="{{ asset('public/js/funciones.js') }}"></script>
		<script type="text/javascript" src="{{ asset('Modules/Admin/Assets/js/login.js') }}"></script>

		<script type="text/javascript">
			var $urlbase = "{{ URL::current() }}", $url = $urlbase + "/";
		</script>
	</body>
</html>