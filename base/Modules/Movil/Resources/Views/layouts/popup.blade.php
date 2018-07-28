<!DOCTYPE html>
<!--[if IE 8]>    <html lang="es" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>    <html lang="es" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="es"><!--<![endif]-->
<head>
	@include('admin::partials.head')
</head><!--/head-->

<body class="page-container-bg-solid body-popup">
	<div class="page-container">
		<div class="page-content-wrapper">
			<div class="page-head">
				<div class="container-fluid">
					<div class="page-title">
						<h1>{{ ucwords($html['titulo']) }}</h1>
					</div>
				</div>
			</div>
			<div class="page-content">
				<div class="container-fluid">
					@yield('content')
					<div id="botonera" class="row">
						<div class="col-sm-12 text-right">
							<button id="guardar" class="btn blue tooltips" data-container="body" data-placement="top" data-original-title="{{ Lang::get('backend.btn_group.save.title') }}">
								<i class="fa fa-floppy-o" aria-hidden="true"></i>
								<span class="visible-lg-inline visible-md-inline">{{ Lang::get('backend.btn_group.save.btn') }}</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('admin::partials.footer')
</body>
</html>