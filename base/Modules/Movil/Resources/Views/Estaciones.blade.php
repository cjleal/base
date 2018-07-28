@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Estaciones']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar Estaciones.',
        'columnas' => [
            'Descripcion' => '50',
		'Estatus' => '50'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST' ]) !!}
            {!! $Estaciones->generate() !!}
        {!! Form::close() !!}
    </div>
@endsection