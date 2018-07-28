@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Ocasiones']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar Ocasiones.',
        'columnas' => [
            'Descripcion' => '100'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST' ]) !!}
            {!! $Ocasiones->generate() !!}
            
            <div class="col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments"></i> Preguntas Relacionadas a la Ocasión 
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle;"> Descripción</th>
                                        <th style="width: 55px;">
                                            <button class="btn blue tooltips agregar" data-container="body" data-placement="top" data-original-title="Eliminar" id_estacion="1">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        {!! Form::close() !!}
    </div>
<table id="tabla-clon">
    <tr>
        <td>
            <input name="descripcionP[]" type="text" class="form-control" value="" /> </div>
        </td>
        <td> 
            <button class="btn red tooltips eliminar" data-container="body" data-placement="top" data-original-title="Eliminar">
                <i class="fa fa-minus"></i>
            </button> 
        </td>
    </tr>
</table>
@endsection

@push('css')
<style type="text/css">
#tabla-clon{
    display: none;
}
</style>
@endpush