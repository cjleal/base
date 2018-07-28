@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Tipo Prenda']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar TipoPrenda.',
        'columnas' => [
            'Descripcion' => '33.333333333333'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST' ]) !!}
            {!! $TipoPrenda->generate() !!}
            {{ Form::bsSelect('prenda_relacion_id', $controller->Prenda(), '', [
                    'label' => 'Tipos de Prendas para Combinar',
                    'required' => 'required',
                    'class' => 'bs-select',
                    'multiple' => 'multiple',
                    'name'    => 'prenda_relacion_id[]',
                    'id'=>'prenda_relacion_id'
            ]) }}
        {!! Form::close() !!}
    </div>
@endsection