	<ul class="page-breadcrumb breadcrumb">
		<li>
			<a href="{{ url('/') }}">Inicio</a><i class="fa fa-circle"></i>
		</li>
		@foreach ($ubicacion as $ubi)
		<li>
			<span>{{ $ubi }} 
			@if (!$loop->last)
			<i class="fa fa-circle"></i>
			@endif
			</span>
		</li>
		@endforeach
	</ul>