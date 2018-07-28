	<div class="page-header">
		<!-- BEGIN HEADER TOP -->
		<div class="page-header-top">
			<div class="container-fluid">
				<!-- BEGIN LOGO -->
				<!--<div class="page-logo">
					<a href="{{url('/')}}">
						<img src="{{ asset('public/img/logos/logo.png') }}" alt="logo" class="logo-default" />
					</a>
				</div>
				-->
				<div class="page-logo">
					<div class="titulo_sistema"><label>K H A L E E S I</label></div>
				</div>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="menu-toggler"></a>
				<!-- END RESPONSIVE MENU TOGGLER -->
				<!-- BEGIN TOP NAVIGATION MENU -->
				<div class="top-menu">
					<ul class="nav navbar-nav pull-right">
						<!-- BEGIN USER LOGIN DROPDOWN -->
						<li class="dropdown dropdown-user dropdown-dark">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								@if(is_file('public/img/usuarios/' . $usuario->usuario . '.jpg'))
									<img alt="" class="img-circle" src="{{ url('public/img/usuarios/' . $usuario->usuario . '.jpg') }}">
								@else
									<img alt="" class="img-circle" src="{{ url('public/img/usuarios/user.png') }}">
								@endif
								<span class="username username-hide-mobile">{{ $usuario->nombre }}</span>
							</a>
							@if($usuario->id > 0)
							<ul class="dropdown-menu dropdown-menu-default">
								<li>
									<a href="{{ url(Config::get('admin.prefix').'/perfil') }}">
										<i class="fa fa-user"></i> Mi Perfil
									</a>
								</li>
								<li>
									<a href="{{ url(Config::get('admin.prefix').'/login/salir') }}">
										<i class="icon-logout"></i> Salir
									</a>
								</li>
							</ul>
							@endif
						</li>
						<!-- END USER LOGIN DROPDOWN -->
						<!-- BEGIN QUICK SIDEBAR TOGGLER -->
						<li class="">
							<a href="{{ url(Config::get('admin.prefix').'/login/salir') }}">
								<span class="sr-only">Salir</span>
								<i class="icon-logout"></i> 
							</a>
						</li>
						<!-- END QUICK SIDEBAR TOGGLER -->
					</ul>
				</div>
				<!-- END TOP NAVIGATION MENU -->
			</div>
		</div>
		<!-- END HEADER TOP -->
		@include('admin::partials.menu')
	</div>