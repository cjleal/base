@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Api Estilos']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar ApiEstilos.',
        'columnas' => [
            'Nombre' => '50',
		'Descripcion' => '50'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST' ]) !!}
            {!! $ApiEstilos->generate() !!}
        {!! Form::close() !!}
    </div>
@endsection