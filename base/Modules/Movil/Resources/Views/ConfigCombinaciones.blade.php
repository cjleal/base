@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Config Combinaciones']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar ConfigCombinaciones.',
        'columnas' => [
            'Prenda Principal' => '16.666666666667',
		    'Descripcion' => '16.666666666667'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        <form method="POST" action="http://localhost/cristian/ConfigCombinaciones" accept-charset="UTF-8" id="formulario" name="formulario"><input name="_token" type="hidden" value="IeRAYhF0Ms3amvCz1i39uw6CyPSw8ak199ZAFUnO">
            {{csrf_field()}}
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label for="descripcion">Descripcion de la Configuración:</label>
                <input placeholder="Descripcion" id="descripcion" class="form-control" name="descripcion" type="text">
            </div>
            <div class="col-md-12">
                <div class="portlet box green">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-comments"></i>Prenda Principal 
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table1 table-bordered table-hover" style="min-height: 357px;">
                                <thead>
                                    <tr>
                                        <th><label for="r">Categoria de Prendas</label></th>
                                        <th><label for="r">Detalle de Prenda</label></th>
                                        <th><label for="r">Sexo</label></th>
                                        <th><label for="r">Estaciones</label></th>
                                        <th><label for="r">Tono de Piel</label></th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select id="prenda_princ_id" class="form-control" name="prenda_princ_id">
                                                <option selected="selected" value="">Seleccione</option>
                                                @foreach($tiposPrendas as $prenda)
                                                    <option selected="" value="{{ $prenda->id }}">{{ $prenda->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            {{ Form::bsSelect(' ', [], '', [
                                                    'required' => 'required',
                                                    'class' => 'bs-select',
                                                    'multiple' => 'multiple',
                                                    'name'    => 'tipoprendadetalle_id[]',
                                                    'id'=>'tipoprendadetalle_id'
                                            ]) }}
                                        </td>
                                        <td>
                                            {{ Form::bsSelect(' ', $controller->Sexo(), '', [
                                                    'required' => 'required',
                                                    'class' => 'bs-select',
                                                    'multiple' => 'multiple',
                                                    'name'    => 'sexo[]',
                                                    'id'=>'sexo'
                                            ]) }}
                                        </td>
                                        <td>
                                            {{ Form::bsSelect(' ', $controller->Estacion(), '', [
                                                    'required' => 'required',
                                                    'class' => 'bs-select',
                                                    'multiple' => 'multiple',
                                                    'name'    => 'estacion_id[]',
                                                    'id'    => 'estacion_id'
                                            ]) }}
                                        </td>
                                        <td>
                                            {{ Form::bsSelect(' ', $controller->TonoPieles(), '', [
                                                    'required' => 'required',
                                                    'class' => 'bs-select',
                                                    'multiple' => 'multiple',
                                                    'name'    => 'tonopiel_id[]',
                                                    'id'    => 'tonopiel_id'
                                            ]) }}
                                        </td>
                                     </tr>
                                     <tr>
                                        <td ><label for="r">Ocasiones</label></td>
                                        <td colspan='4'>
                                            {{ Form::bsSelect(' ', $controller->TipoOcasiones(), '', [
                                                    'required' => 'required',
                                                    'class' => 'bs-select',
                                                    'multiple' => 'multiple',
                                                    'name'    => 'ocasiones_id[]',
                                                    'id'    => 'ocasiones_id'
                                            ]) }}
                                        </td>

                                     </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan='4' style="text-align: center;"><label for="r">Colores de las Prendas Principales</label> </td>
                                        <th >
                                            <button class="btn blue tooltips agregarColor" style="float: right;" data-container="body" data-placement="top" data-original-title="Agregar" id_principal="0">
                                                 <i class="fa fa-plus"></i>
                                            </button> 
                                        </th>
                                    </tr>
                                    <tr class="colores">
                                        <td colspan='4'>
                                            <input placeholder="Color" class="colorpicker-default form-control colorp" name="color[]" type="text">
                                        </td>
                                        <td>
                                            <button class="btn red tooltips eliminarPrincipal" style="float: right;" data-container="body" data-placement="top" data-original-title="Eliminar">
                                                 <i class="fa fa-minus"></i>
                                            </button> 
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--  tabla !-->
            <div class='combinar col-md-12'></div>
        </form>

    </div>
<table id="tabla-clon-principal" style="display: none;">
    <tr class="">
        <td colspan='4'>
            <input placeholder="Color" class="colorpicker-default form-control" name="color[]" type="text">
        </td>
        <td>
            <button class="btn red tooltips eliminarPrincipal" style="float: right;" data-container="body" data-placement="top" data-original-title="Eliminar">
                 <i class="fa fa-minus"></i>
            </button> 
        </td>
    </tr>
</table>
<table id="tabla-clon" style="display: none;">
    <tr>
        <td colspan="2">
            <input name="descripcionCombina[][]" type="text" class="colorpicker-default form-control" value="">
        </td>  
        <td >
            <button class="btn red tooltips eliminar" data-container="body" data-placement="top" data-original-title="Eliminar">
                 <i class="fa fa-minus"></i>
            </button> 
        </td>
    </tr>
</table>
@endsection
@push('css')
<style type="text/css">
label{
    font-weight: 700;
}
.table1 th{
    text-align: center;
}
</style>
@endpush
@push('js')
<script type="text/javascript" src="http://localhost/cristian/public/plugins/bootstrap-select/js/bootstrap-multiselect.js"></script>

@endpush