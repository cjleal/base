@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Turnos']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar Turnos.',
        'columnas' => [
            'Descripcion' => '100'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST' ]) !!}
            {!! $Turnos->generate() !!}
        {!! Form::close() !!}
    </div>
@endsection