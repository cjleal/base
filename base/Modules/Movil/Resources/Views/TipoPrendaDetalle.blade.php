@extends(isset($layouts) ? $layouts : 'admin::layouts.default')

@section('content-top')
    @include('admin::partials.botonera')
    
    @include('admin::partials.ubicacion', ['ubicacion' => ['Tipo Prenda Detalle']])
    
    @include('admin::partials.modal-busqueda', [
        'titulo' => 'Buscar TipoPrendaDetalle.',
        'columnas' => [
            'Descripcion' => '50'
        ]
    ])
@endsection

@section('content')
    <div class="row">
        <form method="POST" action="http://localhost/cristian/TipoPrendaDetalle" accept-charset="UTF-8" id="formulario" name="formulario"><input name="_token" type="hidden" value="zlshepzq9Fh8KQfyup5L6EPDsN23MsRKLIBNoNkq">
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label for="tipo_prenda_id">Categoria de Prendas:</label>
                <select id="tipo_prenda_id" class="form-control" name="tipo_prenda_id">
                    <option selected="selected" value="">Seleccione</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>    
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <label for="descripcion">Descripcion:</label>
                <input placeholder="Descripcion  de la Prenda" id="descripcion" class="form-control" name="descripcion" type="text">
            </div>
        </form>
    </div>
@endsection