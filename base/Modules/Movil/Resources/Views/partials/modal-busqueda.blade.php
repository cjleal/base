<div id="{{ $id ?? 'modalTablaBusqueda' }}" class="modal modal-busqueda fade" tabindex="-1" role="dialog">
	<div class="modal-dialog container">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">{{ $titulo ?? 'Buscar' }}

				<div class="btn-group btn-datatable">
					<button type="button" class="btn btn-primary dropdown-toggle btn-sm" aria-haspopup="true" aria-expanded="false">
						Acciones <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						@if ($controller->permisologia($controller->ruta() . '/restaurar') || $controller->permisologia($controller->ruta() . '/destruir'))
							<li class="dropdown-header">Registros Eliminados</li>

							<li data-re="1">
								<a href="#">
									<i class="fa fa-eye" aria-hidden="true"></i> 
									Ver Registros Eliminados 
									<i class="check" aria-hidden="true"></i>
								</a>
							</li>

							<li data-sre="1">
								<a href="#">
									<i class="fa fa-eye" aria-hidden="true"></i> 
									Ver Solo Registros Eliminados 
									<i class="check" aria-hidden="true"></i>
								</a>
							</li>
							
							<li role="separator" class="divider"></li>
						@endif
						<li data-vfc="1">
							<a href="#">
								<i class="fa fa-search" aria-hidden="true"></i> 
								Ver filtros por Comluna
								<i class="fa fa-check check" aria-hidden="true"></i>
							</a>
						</li>
						
						<li role="separator" class="divider"></li>

						<li class="dropdown-header">Visualizar Columnas</li>
						@foreach($columnas as $columna => $ancho)
						<li data-column="{{ $loop->iteration - 1 }}">
							<a href="#">
								{{ $columna }}
								<i class="fa fa-check check" aria-hidden="true"></i>
							</a>
						</li>
						@endforeach
					</ul>
				</div>
				</h4>
			</div>
			<div class="modal-body">
				@stack('filtroDatatable')
				<table id="{{ $idTabla ?? 'tabla' }}" class="table table-striped table-hover table-bordered">
					<thead>
						<tr>
							@foreach($columnas as $columna => $ancho)
							<th style="width: {{ $ancho }}%;">{{ $columna }}</th>
							@endforeach
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							@foreach($columnas as $columna => $ancho)
							<th style="width: {{ $ancho }}%;">{{ $columna }}</th>
							@endforeach
						</tr>
					</tfoot>
				</table>
			</div>
			<!--<div class="modal-footer">
				<button type="button" class="btn blue" data-dismiss="modal">Cerrar</button>
			</div>-->
		</div>
	</div>
</div>