@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Colores Imponen Estacion']])
    
    
@endsection

@section('content')
    <div class="row">
        {!! Form::open(['id' => 'formulario', 'name' => 'formulario', 'method' => 'POST' ]) !!}

        @foreach($estaciones as $estacion)
            <div class="col-md-12">
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments"></i> {{ $estacion->descripcion }} 
                        </div>
                    </div>
                    <input type="hidden" id="estacion_id" name="estacion_id[]" class="form-control" value="{{ $estacion->id }}">
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle;"> Color </th>
                                        <th style="width: 55px;">
                                            <button class="btn blue tooltips agregar" data-container="body" data-placement="top" data-original-title="Eliminar" id_estacion='{{ $estacion->id }}'>
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($EstacionesGuardadas->where('estaciones_id', $estacion->id) as $color)
                                        <tr>
                                            <td>
                                                <input name="descripcion[{{ $estacion->id }}][]" type="text" class="colorpicker-default form-control" value="{{$color->descripcion}}" /> </div>
                                            </td>
                                            <td> 
                                                <button class="btn red tooltips eliminar" data-container="body" data-placement="top" data-original-title="Eliminar">
                                                    <i class="fa fa-minus"></i>
                                                </button> 
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                <input name="descripcion[{{ $estacion->id }}][]" type="text" class="colorpicker-default form-control" value="" /> </div>
                                            </td>
                                            <td> 
                                                <button class="btn red tooltips eliminar" data-container="body" data-placement="top" data-original-title="Eliminar">
                                                    <i class="fa fa-minus"></i>
                                                </button> 
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
        {!! Form::close() !!}
    </div>
<table id="tabla-clon">
    <tr>
        <td>
            <input name="descripcion[]" type="text" class="colorpicker-default form-control" value="#fffff" /> </div>
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