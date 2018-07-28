	<div class="page-header-menu">
		<div class="container-fluid">
			<div class="hor-menu">
				<ul class="nav navbar-nav">
					{!! \Modules\Admin\Model\Menu::generar_menu($controller->app) !!}
				</ul>
			</div>
		</div>
	</div>